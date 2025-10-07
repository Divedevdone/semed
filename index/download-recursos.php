<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed

if (!isset($_GET['id'])) {
    die("ID não informado.");
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT nome_arquivo, tipo_arquivo, arquivo FROM adicionais WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nome, $tipo, $arquivo);

if ($stmt->fetch()) {
    // Evitar problemas com caracteres estranhos no nome
    $nomeSeguro = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $nome);

    header("Content-Type: $tipo");
    header("Content-Disposition: attachment; filename=\"" . $nomeSeguro . "\"");
    header("Content-Length: " . strlen($arquivo));
    header("Cache-Control: no-cache");
    header("Pragma: no-cache");

    echo $arquivo; // Envia os bytes do arquivo
    exit;
} else {
    echo "Arquivo não encontrado.";
}

$stmt->close();
$conn->close();
?>
