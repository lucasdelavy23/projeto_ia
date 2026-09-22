// =====================================================
// REGRAS DE JAVASCRIPT GLOBAIS DO PAINEL ADMINISTRATIVO
// Carregado no fim de todas as páginas (src/includes/footer.php)
// Depende de: Bootstrap 5 (bundle) e htmx 2 (ambos via CDN).
// =====================================================

(function () {
	'use strict';

	// ---------------------------------------------------
	// 1. REGISTRO CONCLUÍDO COM SUCESSO
	// O parcial clientes/partials/save.php responde com o header
	// "HX-Trigger: clienteSalvo" apenas quando o INSERT funciona.
	// Aqui fechamos o modal e recarregamos a listagem (table.php).
	// ---------------------------------------------------
	document.addEventListener('clienteSalvo', function () {
		fecharModal('modal-cliente');
		recarregarViaHtmx('tabela-clientes');
	});

	// ---------------------------------------------------
	// 2. MODAL DE CLIENTE FECHADO -> LIMPA O FORMULÁRIO
	// Evita que valores digitados ou mensagens de erro fiquem
	// "presos" no modal quando o usuário desiste da inclusão.
	// ---------------------------------------------------
	document.addEventListener('hidden.bs.modal', function (evento) {
		if (evento.target && evento.target.id === 'modal-cliente') {
			limparFormularioCliente();
		}
	});

	// ---------------------------------------------------
	// 3. VALIDAÇÃO HTML5 VISÍVEL AO USUÁRIO
	// O htmx não envia formulários inválidos, mas por padrão também
	// não mostra o motivo ao usuário. Aqui reexibimos os avisos
	// nativos do navegador. A captura (true) garante que rodamos
	// antes do listener de submit do próprio htmx.
	// ---------------------------------------------------
	document.addEventListener('submit', function (evento) {
		const formulario = evento.target;

		if (!(formulario instanceof HTMLFormElement) || !formulario.hasAttribute('hx-post')) {
			return;
		}

		if (typeof formulario.checkValidity === 'function' && !formulario.checkValidity()) {
			formulario.reportValidity();
		}
	}, true);

	// ---------------------------------------------------
	// FUNÇÕES AUXILIARES
	// ---------------------------------------------------

	/**
	 * Fecha um modal do Bootstrap pelo id do elemento.
	 */
	function fecharModal(idModal) {
		const elemento = document.getElementById(idModal);

		if (elemento && window.bootstrap) {
			bootstrap.Modal.getOrCreateInstance(elemento).hide();
		}
	}

	/**
	 * Recarrega um bloco de conteúdo via htmx, reaproveitando o hx-get
	 * e o hx-swap já declarados no próprio elemento na página.
	 * Ex.: <div id="tabela-clientes" hx-get="/clientes/?partial=table" ...>
	 */
	function recarregarViaHtmx(idElemento) {
		const elemento = document.getElementById(idElemento);
		const url = elemento ? elemento.getAttribute('hx-get') : null;

		if (!elemento || !url || !window.htmx) {
			return;
		}

		htmx.ajax('GET', url, { target: elemento, swap: 'innerHTML' });
	}

	/**
	 * Limpa o formulário de inclusão de cliente (valores e mensagens).
	 */
	function limparFormularioCliente() {
		const formulario = document.getElementById('form-cliente');

		if (!formulario) {
			return;
		}

		formulario.reset();

		formulario.querySelectorAll('.is-invalid').forEach(function (campo) {
			campo.classList.remove('is-invalid');
		});

		const alerta = formulario.querySelector('.alert-danger');

		if (alerta) {
			alerta.remove();
		}
	}
})();
