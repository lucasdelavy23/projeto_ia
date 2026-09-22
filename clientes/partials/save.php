<?php
// =====================================================
// PARCIAL: INCLUSÃO DE CLIENTE (POST VIA HTMX)
// Endpoint: /clientes/?partial=save
//
// Responde sempre com o formulário (partials/form.php):
//   - sucesso -> formulário limpo + header "HX-Trigger: clienteSalvo"
//   - erro    -> formulário com os valores digitados + as mensagens
//
// O código HTTP é 200 nos dois casos, porque o htmx só realiza o
// swap em respostas 2xx (e é o swap que devolve os erros ao modal).
// =====================================================

require_once __DIR__ . '/../../src/includes/bd.php';

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
	http_response_code(405);
	exit('<div class="alert alert-danger m-3 mb-0">Este endereço aceita apenas requisições POST.</div>');
}

// =====================================================
// 1. VALORES RECEBIDOS
// =====================================================

$cliente = [
	'nome'     => trim((string) ($_POST['nome'] ?? '')),
	'cpf'      => trim((string) ($_POST['cpf'] ?? '')),
	'email'    => trim((string) ($_POST['email'] ?? '')),
	'telefone' => trim((string) ($_POST['telefone'] ?? '')),
];

$erros = [];
$erroGeral = '';

// =====================================================
// 2. VALIDAÇÃO E NORMALIZAÇÃO DOS CAMPOS
// =====================================================

// Nome
if ($cliente['nome'] === '') {
	$erros['nome'] = 'Informe o nome do cliente.';
} elseif (mb_strlen($cliente['nome']) > 150) {
	$erros['nome'] = 'O nome deve ter no máximo 150 caracteres.';
}

// CPF: exige 11 dígitos e grava no padrão 000.000.000-00
$cpfNumeros = preg_replace('/\D/', '', $cliente['cpf']);

if ($cpfNumeros === '') {
	$erros['cpf'] = 'Informe o CPF do cliente.';
} elseif (strlen($cpfNumeros) !== 11) {
	$erros['cpf'] = 'O CPF deve conter 11 dígitos.';
} else {
	$cliente['cpf'] = sprintf(
		'%s.%s.%s-%s',
		substr($cpfNumeros, 0, 3),
		substr($cpfNumeros, 3, 3),
		substr($cpfNumeros, 6, 3),
		substr($cpfNumeros, 9, 2)
	);
}

// E-mail
if ($cliente['email'] === '') {
	$erros['email'] = 'Informe o e-mail do cliente.';
} elseif (mb_strlen($cliente['email']) > 150) {
	$erros['email'] = 'O e-mail deve ter no máximo 150 caracteres.';
} elseif (!filter_var($cliente['email'], FILTER_VALIDATE_EMAIL)) {
	$erros['email'] = 'Informe um e-mail válido (ex.: nome@dominio.com).';
} else {
	$cliente['email'] = mb_strtolower($cliente['email']);
}

// Telefone (opcional): aceita 10 ou 11 dígitos e grava como (00) 00000-0000
if ($cliente['telefone'] !== '') {
	$telefoneNumeros = preg_replace('/\D/', '', $cliente['telefone']);
	$quantidadeDigitos = strlen($telefoneNumeros);

	if ($quantidadeDigitos !== 10 && $quantidadeDigitos !== 11) {
		$erros['telefone'] = 'O telefone deve ter 10 ou 11 dígitos, com DDD.';
	} elseif ($quantidadeDigitos === 11) {
		$cliente['telefone'] = sprintf(
			'(%s) %s-%s',
			substr($telefoneNumeros, 0, 2),
			substr($telefoneNumeros, 2, 5),
			substr($telefoneNumeros, 7, 4)
		);
	} else {
		$cliente['telefone'] = sprintf(
			'(%s) %s-%s',
			substr($telefoneNumeros, 0, 2),
			substr($telefoneNumeros, 2, 4),
			substr($telefoneNumeros, 6, 4)
		);
	}
}

// =====================================================
// 3. UNICIDADE (só consulta o banco quando os campos estão ok)
// =====================================================

if ($erros === []) {
	$consulta = bd()->prepare(
		'SELECT cpf, email
		FROM cliente
		WHERE cpf = :cpf OR email = :email'
	);
	$consulta->execute([
		':cpf'   => $cliente['cpf'],
		':email' => $cliente['email'],
	]);

	foreach ($consulta->fetchAll() as $existente) {
		if ($existente['cpf'] === $cliente['cpf']) {
			$erros['cpf'] = 'Já existe um cliente com este CPF.';
		}

		if (mb_strtolower((string) $existente['email']) === $cliente['email']) {
			$erros['email'] = 'Já existe um cliente com este e-mail.';
		}
	}
}

// =====================================================
// 4. GRAVAÇÃO
// =====================================================

$salvo = false;

if ($erros === []) {
	try {
		$insercao = bd()->prepare(
			'INSERT INTO cliente (nome, cpf, email, telefone)
			VALUES (:nome, :cpf, :email, :telefone)'
		);
		$insercao->execute([
			':nome'     => $cliente['nome'],
			':cpf'      => $cliente['cpf'],
			':email'    => $cliente['email'],
			':telefone' => $cliente['telefone'] === '' ? null : $cliente['telefone'],
		]);

		$salvo = true;
	} catch (PDOException $e) {
		error_log('clientes/save.php: ' . $e->getMessage());

		$erroGeral = $e->getCode() === '23000'
			? 'CPF ou e-mail já cadastrado para outro cliente.'
			: 'Não foi possível salvar o cliente. Tente novamente.';
	}
}

// =====================================================
// 5. RESPOSTA (sempre o formulário)
// =====================================================

if ($salvo) {
	// O src/assets/js/app.js escuta este evento para fechar o modal
	// e recarregar a listagem de clientes (?partial=table)
	header('HX-Trigger: clienteSalvo');

	// Devolve o formulário limpo, pronto para uma nova inclusão
	$cliente = [];
}

require __DIR__ . '/form.php';
