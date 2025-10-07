<?php
include 'conexao.php';

$usuario = "admin";  
$senha = password_hash("1234", PASSWORD_DEFAULT); // senha segura

$sql = "INSERT INTO usuarios (email, usuario, senha) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario, $senha);
$stmt->execute();

echo "Usuário cadastrado com sucesso!";
