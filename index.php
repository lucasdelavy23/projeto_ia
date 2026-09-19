<?php $pagina = $_GET['pagina'] ?? 'inicio'; ?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Painel administrativo</title>
	<link href="src/assets/css/app.css" rel="stylesheet">
</head>
<body class="bg-light">
	<header class="navbar navbar-expand bg-white border-bottom shadow-sm px-3 px-md-4">
		<a class="navbar-brand" href="?pagina=inicio">
			<img src="https://placehold.co/150x45/eaf2ff/0d6efd?text=LOGO" alt="Logo" width="150" height="45" class="rounded">
		</a>
		<div class="dropdown ms-auto">
			<button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
				<i class="fa-solid fa-circle-user text-primary me-2"></i>Lucas
			</button>
			<ul class="dropdown-menu dropdown-menu-end shadow-sm">
				<li><a class="dropdown-item text-danger" href="#"><i class="fa-solid fa-right-from-bracket me-2"></i>Sair</a></li>
			</ul>
		</div>
	</header>

	<div class="container-fluid">
		<div class="row min-vh-100">
			<aside class="col-12 col-md-3 col-lg-2 bg-white border-end p-3">
				<div class="text-uppercase text-secondary small fw-semibold mb-2">Navegação</div>
				<nav class="nav nav-pills flex-column gap-1">
					<a class="nav-link <?= $pagina === 'inicio' ? 'active' : 'text-dark' ?>" href="?pagina=inicio"><i class="fa-solid fa-chart-line fa-fw me-2"></i>Visão geral</a>
					<a class="nav-link <?= $pagina === 'clientes' ? 'active' : 'text-dark' ?>" href="?pagina=clientes"><i class="fa-solid fa-users fa-fw me-2"></i>Clientes</a>
					<a class="nav-link <?= $pagina === 'produtos' ? 'active' : 'text-dark' ?>" href="?pagina=produtos"><i class="fa-solid fa-box-open fa-fw me-2"></i>Produtos</a>
					<a class="nav-link <?= $pagina === 'pedidos' ? 'active' : 'text-dark' ?>" href="?pagina=pedidos"><i class="fa-solid fa-cart-shopping fa-fw me-2"></i>Pedidos</a>
				</nav>
			</aside>

			<main class="col-12 col-md-9 col-lg-10 p-3 p-md-4">
				<div class="mb-4">
					<h1 class="h3 mb-1">Olá, Lucas!</h1>
					<p class="text-secondary mb-0">Acompanhe as informações do seu sistema.</p>
				</div>
				<div class="row g-3">
					<div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary">Clientes</div><strong class="fs-3">248</strong><i class="fa-solid fa-users float-end text-primary fs-4"></i></div></div></div>
					<div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary">Produtos</div><strong class="fs-3">126</strong><i class="fa-solid fa-box-open float-end text-success fs-4"></i></div></div></div>
					<div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary">Pedidos</div><strong class="fs-3">532</strong><i class="fa-solid fa-cart-shopping float-end text-warning fs-4"></i></div></div></div>
					<div class="col-sm-6 col-xl-3"><div class="card border-0 shadow-sm"><div class="card-body"><div class="text-secondary">Faturamento</div><strong class="fs-3">R$ 18.420</strong><i class="fa-solid fa-chart-line float-end text-info fs-4"></i></div></div></div>
				</div>
				<div class="card border-0 shadow-sm mt-4"><div class="card-body"><h2 class="h5">Conteúdo da página</h2><p class="text-secondary mb-0">Use o menu para acessar clientes, produtos ou pedidos.</p></div></div>
			</main>
		</div>
	</div>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
