<?php
$servername = "mysql:host=localhost;dbname=empresa";
$username = "chebs";
$password = "chebs";

try {
    $conn = new PDO($servername, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND=>"SET NAMES utf8"));
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conectado...";
    }
catch(PDOException $e)
    {
    echo "Falló la conexion! " . $e->getMessage();
    }
?>
