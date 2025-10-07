<?php
include "../conexao.php"; // ajuste o caminho se necessário

if (!isset($_GET['id'])) {
    die("Arquivo não especificado.");
}

$id = intval($_GET['id']);

$sql = "SELECT nome_arquivo, tipo_arquivo, arquivo 
        FROM cursos 
        WHERE id = $id";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Define tipo e abre inline
    header("Content-Type: " . $row['tipo_arquivo']);
    header("Content-Disposition: inline; filename=\"" . $row['nome_arquivo'] . "\"");
    header("Content-Length: " . strlen($row['arquivo']));

    echo $row['arquivo'];
    exit;
} else {
    echo "Arquivo não encontrado.";
}
