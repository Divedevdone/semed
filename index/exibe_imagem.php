<?php
include __DIR__ . '/../conexao.php';

$id = intval($_GET['id']);
$sql = "SELECT imagem, tipo_imagem FROM cronograma WHERE id = $id";
$result = $conn->query($sql);

if ($row = $result->fetch_assoc()) {
    header("Content-Type: " . $row['tipo_imagem']);
    echo $row['imagem']; // blob
}
?>
