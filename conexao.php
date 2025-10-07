<?php
$servername = "localhost";
$username = "root";   // usuário padrão do XAMPP
$password = "";       // senha vazia por padrão no XAMPP
$dbname = "db-semed"; // nome do banco

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}
?>
