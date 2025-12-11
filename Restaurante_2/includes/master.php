<!DOCTYPE html>
<html lang="pt">
<?php
// No topo do master.php
$content = $content ?? '';
$title = $title ?? 'eatEasy';
?>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Viewport !-->
    <link rel="stylesheet" href="https://use.typekit.net/zcu4bcg.css"> <!-- Font !-->
    <link rel="stylesheet" href="/Restaurante_2/public/css/index.css">
    <link rel="stylesheet" href="/Restaurante_2/public/css/header.css"> <!--chamar o header.css!-->
    <link rel="stylesheet" href="/Restaurante_2/public/css/footer.css"> <!--chamar o header.css!-->
    <title><?= $title ?? 'eatEasy' ?></title>
</head>

<body>
    <?php require 'header.php'; ?>
    <?php require $content; ?>
    <?php require 'footer.php'; ?>
</body>