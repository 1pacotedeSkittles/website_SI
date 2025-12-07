<!DOCTYPE html>
<html lang="pt">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport !-->
    <link rel="stylesheet" href="https://use.typekit.net/zcu4bcg.css"> <!-- Font !-->
    <title>eatEasy</title>
    <link rel="stylesheet" href="../../css/index.css">
    <link rel="stylesheet" href="../../css/navbar.css">
    <link rel="stylesheet" href="../../css/footer.css">
</head>

<body>

<?php
require '../includes/navbar.php';
?>

<main id="restaurantes">
    <div>
        <h1>Faça já a sua reserva</h1>
    </div>

    <article>
        <div>
            <img src="/fotos/foto.webp" height="800" width="600" alt="foto1"/>
        </div>
        <div>
            <h3>Restaurante 1</h3>
            <p>localização</p>
            <p>Tipo de comida</p>
            <p>Preço médio por prato</p>
        </div>

        <div>
            <img src="../../../fotos/foto.webp" height="800" width="600" alt="foto2"/>
        </div>
        <div>
            <h3>Restaurante 2</h3>
            <p>localização</p>
            <p>Tipo de comida</p>
            <p>Preço médio por prato</p>
        </div>

        <div>
            <img src="../../../fotos/foto.webp" height="800" width="600" alt="foto3"/>
        </div>
        <div>
            <h3>Restaurante 3</h3>
            <p>localização</p>
            <p>Tipo de comida</p>
            <p>Preço médio por prato</p>
        </div>

        <div>
            <img src="../../../fotos/foto.webp" height="800" width="600" alt="foto4"/>
        </div>
        <div>
            <h3>Restaurante 4</h3>
            <p>localização</p>
            <p>Tipo de comida</p>
            <p>Preço médio por prato</p>
        </div>
    </article>
</main>

<footer>
    <?php
    require 'footer.php';
    ?>
</footer>

</body>
</html>
