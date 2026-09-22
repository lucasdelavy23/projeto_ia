<?php
// =====================================================
// MÓDULO CLIENTES - FRONT CONTROLLER
// Decide o que será respondido:
//   ?partial=<nome> -> devolve apenas o parcial (usado pelo htmx)
//   qualquer outra  -> devolve a página completa
// =====================================================

$partial = $_GET['partial'] ?? '';

// Lista branca: impede que arquivos arbitrários do projeto
// sejam carregados através do parâmetro ?partial=
$partialsPermitidos = ['table', 'form', 'save'];

if ($partial !== '' && in_array($partial, $partialsPermitidos, true)) {
	require __DIR__ . '/partials/' . $partial . '.php';
	exit;
}

require __DIR__ . '/pages/index.php';