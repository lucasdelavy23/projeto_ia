<?php $pagina = $pagina ?? ($_GET['pagina'] ?? 'inicio'); ?>
<!doctype html>
<html lang="pt-BR">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Painel administrativo</title>
	<link href="/src/assets/css/app.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/htmx.org@2.0.4/dist/htmx.min.js" defer></script>
</head>
<body class="bg-light">
	<header class="navbar navbar-expand bg-white border-bottom shadow-sm px-3 px-md-4">
		<a class="navbar-brand" href="/index.php?pagina=inicio">
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
