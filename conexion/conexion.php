<?php
$servername = "localhost";
$username = "chebs";
$password = "chebs";

try {
    $conn = new PDO("mysql:host=$servername;dbname=empresa", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conectado Exitosamente! ";
    }
catch(PDOException $e)
    {
    echo "Falló la conexion! " . $e->getMessage();
    }
?>
