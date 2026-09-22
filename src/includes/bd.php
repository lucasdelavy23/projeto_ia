<?php
// =====================================================
// CONEXÃO COM O BANCO DE DADOS (PDO / MySQL)
// Arquivo reutilizado por todas as páginas e parciais do sistema.
//
// Uso: $clientes = bd()->query('SELECT ...')->fetchAll();
//
// As credenciais reais ficam em src/config/bd.local.php, um arquivo
// que NÃO é versionado (veja o .gitignore). Os valores definidos aqui
// são apenas o padrão de fallback.
//
// Sempre inclua este arquivo com require_once.
// =====================================================

function bd(): PDO
{
	static $pdo = null;

	if ($pdo instanceof PDO) {
		return $pdo;
	}

	$config = [
		'host'    => '127.0.0.1',
		'porta'   => 3306,
		'banco'   => 'lojavestuario',
		'usuario' => 'root',
		'senha'   => '',
		'charset' => 'utf8mb4',
	];

	// As credenciais locais (fora do Git) sobrescrevem os padrões acima
	$arquivoLocal = __DIR__ . '/../config/bd.local.php';
	if (is_file($arquivoLocal)) {
		$config = array_merge($config, require $arquivoLocal);
	}

	try {
		$pdo = new PDO(
			sprintf(
				'mysql:host=%s;port=%d;dbname=%s;charset=%s',
				$config['host'],
				$config['porta'],
				$config['banco'],
				$config['charset']
			),
			$config['usuario'],
			$config['senha'],
			[
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES   => false,
			]
		);
	} catch (PDOException $e) {
		error_log('bd.php: ' . $e->getMessage());
		http_response_code(500);
		exit('<div class="alert alert-danger m-3">'
			. 'Não foi possível conectar ao banco de dados.'
			. '</div>');
	}

	return $pdo;
}