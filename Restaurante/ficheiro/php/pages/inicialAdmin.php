<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>eatEasy - Inicial Admin</title>
</head>
<body>

<header>
    <!-- <nav>
        <div>
            <a href="index.html"><strong>eatEasy</strong></a>
        </div>

        <div>
            <input type="search" placeholder="Pesquisar...">
        </div>

        <div>
            <a href="contactos.html">Contactos</a>
        </div>
    </nav> -->

    <?php
    require '../includes/navbar.php';
    ?>

</header>

<main>

    <section id="conteudo-principal">

        <h2>Os seus restaurantes</h2>

        <div id="cartoes-restaurantes">

            <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
                <img src="imagens/restaurante_admin1.jpg" alt="Foto Restaurante 1" width="300" height="200">

                <div>
                    <h3>Restaurante 1</h3>
                    <ul>
                        <li>Localização</li>
                        <li>Tipo comida</li>
                        <li>Preço médio por prato</li>
                    </ul>
                    <a href="editar_restaurante1.html">editar</a>
                </div>
            </article>

            <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
                <img src="imagens/restaurante_admin2.jpg" alt="Foto Restaurante 2" width="300" height="200">

                <div>
                    <h3>Restaurante 2</h3>
                    <ul>
                        <li>Localização</li>
                        <li>Tipo comida</li>
                        <li>Preço médio por prato</li>
                    </ul>
                    <a href="editar_restaurante2.html">editar</a>
                </div>
            </article>

            <article style="border: 1px solid #ccc; padding: 10px; margin: 15px;">
                <img src="imagens/restaurante_admin3.jpg" alt="Foto Restaurante 3" width="300" height="200">

                <div>
                    <h3>Restaurante 3</h3>
                    <ul>
                        <li>Localização</li>
                        <li>Tipo comida</li>
                        <li>Preço médio por prato</li>
                    </ul>
                    <a href="editar_restaurante3.html">editar</a>
                </div>
            </article>

        </div>
    </section>

    <aside id="menu-admin" style="border: 1px solid #ccc; padding: 15px; margin-top: 20px;">

        <div>
            <h2>Nome Admin</h2>
        </div>

        <section>
            <h3>Os meus restaurantes</h3>

            <h4>Restaurante 1</h4>
            <ul>
                <li><a href="r1_lista_reservas.html">Lista de reservas</a></li>
                <li><a href="r1_historico_avaliacoes.html">Histórico de avaliações</a></li>
                <li><a href="r1_estatisticas.html">Estatísticas</a></li>
            </ul>

            <h4>Restaurante 2</h4>
            <ul>
                <li><a href="r2_lista_reservas.html">Lista de reservas</a></li>
                <li><a href="r2_historico_avaliacoes.html">Histórico de avaliações</a></li>
                <li><a href="r2_estatisticas.html">Estatísticas</a></li>
            </ul>

            <h4>Restaurante 3</h4>
            <ul>
                <li><a href="r3_lista_reservas.html">Lista de reservas</a></li>
                <li><a href="r3_historico_avaliacoes.html">Histórico de avaliações</a></li>
                <li><a href="r3_estatisticas.html">Estatísticas</a></li>
            </ul>
        </section>
    </aside>

</main>

<footer>
    <hr>
    <div>
        <small><a href="#">sobre nós</a> | <a href="#">termos de utilização</a></small>
        <span> | 📷 f X</span>
    </div>
    <div>
        <small>© 2025 eatEasy. Todos os direitos reservados.</small>
    </div>
</footer>

</body>
</html>