<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eatEasy - Inicial Cliente</title>
</head>
<body>

<header>
    <nav>
        <?php
        require '../includes/navbar.php';
        ?>
    </nav>
</header>

<main>
    <hr>

    <h2>Faça já a sua reserva</h2>

    <section id="restaurantes-destaque">

        <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
            <a href="reserva_restaurante1.html">
                <img src="imagens/restaurante_1.jpg" alt="Foto Restaurante 1" width="300" height="200">
            </a>

            <h3>Restaurante 1</h3>
            <ul>
                <li>Localização</li>
                <li>Tipo comida</li>
                <li>Preço médio por prato</li>
            </ul>
        </article>

        <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
            <a href="reserva_restaurante2.html">
                <img src="imagens/restaurante_2.jpg" alt="Foto Restaurante 2" width="300" height="200">
            </a>

            <h3>Restaurante 2</h3>
            <ul>
                <li>Localização</li>
                <li>Tipo comida</li>
                <li>Preço médio por prato</li>
            </ul>
        </article>

        <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
            <a href="reserva_restaurante3.html">
                <img src="imagens/restaurante_3.jpg" alt="Foto Restaurante 3" width="300" height="200">
            </a>

            <h3>Restaurante 3</h3>
            <ul>
                <li>Localização</li>
                <li>Tipo comida</li>
                <li>Preço médio por prato</li>
            </ul>
        </article>

        <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
            <a href="reserva_restaurante4.html">
                <img src="imagens/restaurante_4.jpg" alt="Foto Restaurante 4" width="300" height="200">
            </a>

            <h3>Restaurante 4</h3>
            <ul>
                <li>Localização</li>
                <li>Tipo comida</li>
                <li>Preço médio por prato</li>
            </ul>
        </article>

    </section>
</main>

<footer>
    <hr>
    <div>
        <a href="#">sobre nós</a> | <a href="#">termos de utilização</a>
        <span> | 📷 f X</span>
    </div>
    <div>
        <small>© 2025 eatEasy. Todos os direitos reservados.</small>
    </div>
</footer>

</body>
</html>