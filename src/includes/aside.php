	<div class="container-fluid">
		<div class="row min-vh-100">
			<aside class="col-12 col-md-3 col-lg-2 bg-white border-end p-3">
				<div class="text-uppercase text-secondary small fw-semibold mb-2">Navegação</div>
				<nav class="nav nav-pills flex-column gap-1">
					<a class="nav-link <?= $pagina === 'inicio' ? 'active' : 'text-dark' ?>" href="/index.php?pagina=inicio"><i class="fa-solid fa-chart-line fa-fw me-2"></i>Visão geral</a>
					<a class="nav-link <?= $pagina === 'clientes' ? 'active' : 'text-dark' ?>" href="/clientes/"><i class="fa-solid fa-users fa-fw me-2"></i>Clientes</a>
					<a class="nav-link <?= $pagina === 'produtos' ? 'active' : 'text-dark' ?>" href="/produtos/"><i class="fa-solid fa-box-open fa-fw me-2"></i>Produtos</a>
					<a class="nav-link <?= $pagina === 'pedidos' ? 'active' : 'text-dark' ?>" href="/pedidos/"><i class="fa-solid fa-cart-shopping fa-fw me-2"></i>Pedidos</a>
				</nav>
			</aside>

			<main class="col-12 col-md-9 col-lg-10 p-3 p-md-4">
