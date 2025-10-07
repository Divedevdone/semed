<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// --- BUSCA ---
$sql = "SELECT id, titulo, mensagem, imagem, tipo_imagem, arquivo, nome_arquivo, tipo_arquivo, capa, tipo_capa, criado_em 
        FROM cursos
        ORDER BY criado_em DESC";
$result = $conn->query($sql);

function transformarLinks($texto) {
    // Garante que "www.site.com" vire "http://www.site.com"
    $texto = preg_replace('~\b(www\.[^\s<]+)~i', 'http://$1', $texto);

    // Transforma links (http:// ou https://) em <a>
    $texto = preg_replace(
        '~(https?://[^\s<]+)~i',
        '<a href="$1" target="_blank" style="color:blue; text-decoration:underline;">$1</a>',
        $texto
    );

    return nl2br($texto); // mantém as quebras de linha
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cursos de formação</title>
    <link rel="stylesheet" href="content-all.css">
</head>
<body>
    <h2 class="titulo-principal">Cursos de formação</h2>
    <br><br><br>
    <div class="container">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>

                <?php if (!empty($row['arquivo'])): ?>
                    <div class="item arquivos-card">
                        <h2><?= htmlspecialchars($row['titulo']) ?></h2>
                        <p><?= transformarLinks(htmlspecialchars($row['mensagem'])) ?></p>

                        <?php if (!empty($row['capa'])): ?>
                            <img src="data:<?= $row['tipo_capa'] ?>;base64,<?= base64_encode($row['capa']) ?>" alt="Capa do arquivo">
                        <?php elseif (!empty($row['imagem'])): ?>
                            <img src="data:<?= $row['tipo_imagem'] ?>;base64,<?= base64_encode($row['imagem']) ?>" alt="Imagem do evento">
                        <?php elseif (!empty($row['arquivo'])): ?>

                            <div class="arquivos">
                                <?php
                                    $ext = strtolower(pathinfo($row['nome_arquivo'], PATHINFO_EXTENSION));
                                    $icones = [
                                        'pdf'  => 'icons/pdf.png',
                                        'doc'  => 'icons/doc.png',
                                        'docx' => 'icons/doc.png',
                                        'xls'  => 'icons/xls.png',
                                        'xlsx' => 'icons/xls.png',
                                        'ppt'  => 'icons/ppt.png',
                                        'pptx' => 'icons/ppt.png',
                                        'txt'  => 'icons/txt.png'
                                    ];
                                    $icone = isset($icones[$ext]) ? $icones[$ext] : 'icons/file.png';
                                ?>
                                <div class="arquivo">
                                    <img src="<?= $icone ?>" alt="<?= $ext ?>">
                                </div>
                            </div>
                        <?php endif; ?>

                        <a href="cursos/visualizar-arquivo.php?id=<?= $row['id'] ?>" target="_blank" class="download-link">
                            <?= htmlspecialchars($row['nome_arquivo']) ?>
                        </a>
                        <p class="meta">Criado em: <?= $row['criado_em'] ?></p>
                    </div> <?php else: ?>
                    <div class="item imagem-coluna">
                        <h2 class="titulo-secundario"><?= htmlspecialchars($row['titulo']) ?></h2>

                        <?php if (!empty($row['imagem'])): ?>
                            <img src="data:<?= $row['tipo_imagem'] ?>;base64,<?= base64_encode($row['imagem']) ?>" alt="Imagem">
                        <?php endif; ?>

                        <p class="mensagem"><?= transformarLinks(htmlspecialchars($row['mensagem'])) ?></p>
                        <p class="meta">Criado em: <?= $row['criado_em'] ?></p>
                    </div> <?php endif; ?>

            <?php endwhile; ?>

        <?php else: ?>
            <p style="text-align:center; color:#555;">Nenhum registro encontrado.</p>
        <?php endif; ?>
    </div> <br><br><br>
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>

    <?php if (isset($_SESSION["usuario"])): ?>
        <div class="btn-add" onclick="addDataCursos()" data-autor="🤖 RoboEdu:" data-fala="Editar">
            <span id="btn-icon">✏️</span>
        </div>
        <input type="file" id="hiddenUpload" style="display:none" />
    <?php endif; ?>
</body>
</html>