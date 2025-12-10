// cliente_home.js

document.getElementById("abrirPainel").addEventListener("click", function () {
    const painel = document.getElementById("painel-cliente");
    painel.classList.toggle("painel-fechado");
});
