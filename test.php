<?php

require_once("config/pdo_odbc.php");
$cnn = new conexion("r");
$cnn->conectar();


$usuario = 'admin';
$sql = "SELECT * FROM usuario where usuario = 'admin' ";
$result = $cnn->query($sql);


//$row = $cnn->fetch_row();
//$password_bd = $cnn->result('password');

//echo ($password_bd);

//$cnn->cerrar();

?>

