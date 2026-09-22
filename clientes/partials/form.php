<?php
// =====================================================
// PARCIAL: FORMULÁRIO DE CLIENTE (INCLUSÃO)
// Usado em dois momentos:
//   1) renderização inicial do modal em clientes/pages/index.php
//   2) resposta do POST de /clientes/?partial=save
//
// Variáveis opcionais (definidas por quem inclui este arquivo):
//   $cliente   -> valores dos campos (array)
//   $erros     -> mensagens de erro por campo (array)
//   $erroGeral -> mensagem de erro geral (string)
// =====================================================

$cliente = $cliente ?? [];
$erros = $erros ?? [];
$erroGeral = $erroGeral ?? '';

// Ajudantes locais: classe do campo, valor digitado e mensagem de erro
$classeCampo = static function (string $campo) use ($erros): string {
	return isset($erros[$campo]) ? ' is-invalid' : '';
};

$valorCampo = static function (string $campo) use ($cliente): string {
	return htmlspecialchars((string) ($cliente[$campo] ?? ''), ENT_QUOTES, 'UTF-8');
};

$mensagemCampo = static function (string $campo, string $padrao) use ($erros): string {
	return htmlspecialchars((string) ($erros[$campo] ?? $padrao), ENT_QUOTES, 'UTF-8');
};
?>

<form id="form-cliente"
		hx-post="/clientes/?partial=save"
		hx-target="this"
		hx-swap="outerHTML"
		hx-disabled-elt="find button[type='submit']">

	<div class="modal-body">
<?php if ($erroGeral !== ''): ?>
		<div class="alert alert-danger d-flex align-items-center gap-2" role="alert">
			<i class="fa-solid fa-triangle-exclamation"></i>
			<span><?= htmlspecialchars($erroGeral, ENT_QUOTES, 'UTF-8') ?></span>
		</div>
<?php endif; ?>

		<div class="mb-3">
			<label for="cliente-nome" class="form-label">Nome <span class="text-danger">*</span></label>
			<input type="text" id="cliente-nome" name="nome" class="form-control<?= $classeCampo('nome') ?>"
					maxlength="150" required autocomplete="name" value="<?= $valorCampo('nome') ?>">
			<div class="invalid-feedback"><?= $mensagemCampo('nome', 'Informe o nome do cliente.') ?></div>
		</div>

		<div class="row g-3 mb-3">
			<div class="col-md-6">
				<label for="cliente-cpf" class="form-label">CPF <span class="text-danger">*</span></label>
				<input type="text" id="cliente-cpf" name="cpf" class="form-control<?= $classeCampo('cpf') ?>"
						maxlength="14" inputmode="numeric" required placeholder="000.000.000-00" value="<?= $valorCampo('cpf') ?>">
				<div class="invalid-feedback"><?= $mensagemCampo('cpf', 'Informe o CPF do cliente.') ?></div>
			</div>
			<div class="col-md-6">
				<label for="cliente-telefone" class="form-label">Telefone</label>
				<input type="tel" id="cliente-telefone" name="telefone" class="form-control<?= $classeCampo('telefone') ?>"
						maxlength="15" inputmode="numeric" placeholder="(00) 00000-0000" value="<?= $valorCampo('telefone') ?>">
				<div class="invalid-feedback"><?= $mensagemCampo('telefone', 'Informe um telefone válido.') ?></div>
			</div>
		</div>

		<div class="mb-0">
			<label for="cliente-email" class="form-label">E-mail <span class="text-danger">*</span></label>
			<input type="email" id="cliente-email" name="email" class="form-control<?= $classeCampo('email') ?>"
					maxlength="150" required autocomplete="email" value="<?= $valorCampo('email') ?>">
			<div class="invalid-feedback"><?= $mensagemCampo('email', 'Informe o e-mail do cliente.') ?></div>
		</div>
	</div>

	<div class="modal-footer">
		<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
		<button type="submit" class="btn btn-primary">
			<i class="fa-solid fa-floppy-disk fa-fw me-2"></i>Salvar
		</button>
	</div>
</form>
