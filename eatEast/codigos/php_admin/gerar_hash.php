
<!--//$password_admin = 'admin123'; // A password usada para fazer login-->
<!--//$hash_final = password_hash($password_admin, PASSWORD_DEFAULT);-->
<!--//echo $hash_final;-->
<!--//-->

<?php
$password_admin = 'admin456';
$hash_final = password_hash($password_admin, PASSWORD_DEFAULT);
echo $hash_final;
?>

<!--//$2y$10$w/P/naYgZ8lZ0EnqX4QDTu3NmKrGXWUgeXUtQ76oRC.uJlBtDpF4e//-->
<!--//admin2 pass: admin456//-->
