<?php
// =====================================================
// PARCIAL: TABELA DE CLIENTES
// Carregado pelo htmx através de /clientes/?partial=table
// =====================================================

require_once __DIR__ . '/../../src/includes/bd.php';

$clientes = bd()->query(
	'SELECT id, nome, cpf, email, telefone
	FROM cliente
	ORDER BY nome'
)->fetchAll();
?>

<div class="card-body p-0">
	<div class="table-responsive">
		<table class="table table-hover align-middle mb-0">
			<thead class="table-light">
				<tr>
					<th scope="col">Nome</th>
					<th scope="col">CPF/CNPJ</th>
					<th scope="col">E-mail</th>
					<th scope="col">Telefone</th>
				</tr>
			</thead>
			<tbody>
<?php if ($clientes === []): ?>
				<tr>
					<td colspan="4" class="text-center text-secondary py-4">Nenhum cliente cadastrado.</td>
				</tr>
<?php else: ?>
<?php foreach ($clientes as $cliente): ?>
				<tr>
					<td class="fw-semibold"><?= htmlspecialchars($cliente['nome'], ENT_QUOTES, 'UTF-8') ?></td>
					<td><?= htmlspecialchars((string) $cliente['cpf'], ENT_QUOTES, 'UTF-8') ?></td>
					<td><?= htmlspecialchars((string) $cliente['email'], ENT_QUOTES, 'UTF-8') ?></td>
					<td><?= htmlspecialchars((string) $cliente['telefone'], ENT_QUOTES, 'UTF-8') ?></td>
				</tr>
<?php endforeach; ?>
<?php endif; ?>
			</tbody>
		</table>
	</div>
</div>