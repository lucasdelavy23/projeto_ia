<?php $pagina = 'clientes'; ?>
<?php require __DIR__ . '/../../src/includes/head.php'; ?>
<?php require __DIR__ . '/../../src/includes/aside.php'; ?>

				<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
					<div>
						<h1 class="h3 mb-1">Clientes</h1>
						<p class="text-secondary mb-0">Acompanhe os clientes cadastrados na loja.</p>
					</div>
					<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-cliente">
						<i class="fa-solid fa-user-plus fa-fw me-2"></i>Novo cliente
					</button>
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

				<!-- MODAL: INCLUSÃO DE CLIENTE -->
				<div class="modal fade" id="modal-cliente" tabindex="-1" aria-labelledby="modal-cliente-titulo" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content">
							<div class="modal-header">
								<h2 class="modal-title fs-5" id="modal-cliente-titulo">
									<i class="fa-solid fa-user-plus fa-fw me-2 text-primary"></i>Novo cliente
								</h2>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
							</div>
<?php require __DIR__ . '/../partials/form.php'; ?>
						</div>
					</div>
				</div>

<?php require __DIR__ . '/../../src/includes/footer.php'; ?>