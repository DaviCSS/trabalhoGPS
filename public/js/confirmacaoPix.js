// Libera a confirmação somente após a marcação da conferência do banco.
document.querySelectorAll('.formularioConfirmacaoPix').forEach(function (formulario) {
    const checkbox = formulario.querySelector('input[name="conferiu_extrato"]');
    const botao = formulario.querySelector('button[type="submit"]');

    if (!checkbox || !botao) return;

    function atualizarBotao() {
        botao.disabled = !checkbox.checked;
    }

    checkbox.addEventListener('change', atualizarBotao);
    atualizarBotao();
});
