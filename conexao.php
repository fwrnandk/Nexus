<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "nexus_bd";
$port = 3306;

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $err) {
    echo "Erro em realizar conexão com o banco de dados";
}

?>
