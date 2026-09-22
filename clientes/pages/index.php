<?php $pagina = 'clientes'; ?>
<?php require __DIR__ . '/../../src/includes/head.php'; ?>
<?php require __DIR__ . '/../../src/includes/aside.php'; ?>

				<div class="mb-4">
					<h1 class="h3 mb-1">Clientes</h1>
					<p class="text-secondary mb-0">Acompanhe os clientes cadastrados na loja.</p>
				</div>

				<div id="tabela-clientes" class="card border-0 shadow-sm"
						hx-get="/clientes/?partial=table"
						hx-trigger="load"
						hx-target="this"
						hx-swap="innerHTML">
					<div class="card-body text-secondary d-flex align-items-center gap-2">
						<div class="spinner-border spinner-border-sm text-primary" role="status" aria-hidden="true"></div>
						Carregando clientes...
					</div>
				</div>

<?php require __DIR__ . '/../../src/includes/footer.php'; ?>