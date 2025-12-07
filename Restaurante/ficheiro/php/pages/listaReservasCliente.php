<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eatEasy - Lista de Reservas</title>
</head>
<body>

<header>
    <?php
    require '../includes/navbar.php';
    ?>
</header>

<main>

    <section id="lista-reservas">
        <h2>As suas reservas</h2>

        <div id="cartoes-reservas">

            <article>
                <img src="imagens/reserva_restaurante1.jpg" alt="Foto Restaurante 1" width="250" height="180">
                <div>
                    <h3>Nome restaurante</h3>
                    <ul>
                        <li>Data (d/m/a)</li>
                        <li>Hora (00h:00m.)</li>
                        <li>Estado do reserva: **confirmado**</li>
                    </ul>
                </div>
            </article>

            <article>
                <img src="imagens/reserva_restaurante2.jpg" alt="Foto Restaurante 2" width="250" height="180">
                <div>
                    <h3>Nome restaurante</h3>
                    <ul>
                        <li>Data (d/m/a)</li>
                        <li>Hora (00h:00m.)</li>
                        <li>Estado do reserva: **confirmado**</li>
                    </ul>
                </div>
            </article>

            <article>
                <img src="imagens/reserva_restaurante3.jpg" alt="Foto Restaurante 3" width="250" height="180">
                <div>
                    <h3>Nome restaurante</h3>
                    <ul>
                        <li>Data (d/m/a)</li>
                        <li>Hora (00h:00m.)</li>
                        <li>Estado do reserva: **cancelado**</li>
                    </ul>
                </div>
            </article>

            <article>
                <img src="imagens/reserva_restaurante4.jpg" alt="Foto Restaurante 4" width="250" height="180">
                <div>
                    <h3>Nome restaurante</h3>
                    <ul>
                        <li>Data (d/m/a)</li>
                        <li>Hora (00h:00m.)</li>
                        <li>Estado do reserva: **confirmado**</li>
                    </ul>
                </div>
            </article>

        </div>
    </section>

    <aside id="calendario-resumo">
        <h3>Calendário</h3>

        <fieldset>
            <legend>Calendário</legend>
            <label for="data">Data:</label>
            <input type="date" id="data" name="data" required>
        </fieldset>
    </aside>

</main>

<footer>
    <hr>
    <div>
        <a href="#">sobre nós</a> | <a href="#">termos de utilização</a>
        <span> | 📷 f X</span>
    </div>
    <div>
        <small>© 2025 eatEasy. todos os direitos reservados.</small>
    </div>
</footer>

</body>
</html>