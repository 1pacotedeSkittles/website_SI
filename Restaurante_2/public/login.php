<?php
session_start();

$title='EATEASY';
// login.php
$content = '../src/views/login.view.php';
// ...
require '../includes/master.php'; // Isto executa require $content;
