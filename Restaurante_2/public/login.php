<?php

// login.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
// ...

$title = 'eateasy';
$content = '../src/views/login.view.php';
require '../includes/master.php';
