<?php
session_start();
include __DIR__ . '/../conexao.php'; // conexão com db-semed


if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../login.php");
    exit();
}

// --- EXCLUSÃO ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $sql = "DELETE FROM referencial WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redireciona para evitar "refresh duplicado"
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red'>Erro ao excluir: " . $conn->error . "</p>";
    }
}
$titulo   = trim($_POST['titulo'] ?? ''); // sempre pega o título
$mensagem = trim($_POST['mensagem'] ?? ''); // se não enviar nada, vira string vazia

// --- PROCESSAMENTO DO FORMULÁRIO ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo   = trim($_POST['titulo'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    // Valores padrão
    $conteudoImg = null;
    $tipoImg     = null;
    $conteudoArq = null;
    $nomeArq     = null;
    $tipoArq     = null;
    $conteudoCapa = null;
    $tipoCapa     = null;

    // Se enviou imagem "normal"
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $conteudoImg = file_get_contents($_FILES['imagem']['tmp_name']);
        $tipoImg     = $_FILES['imagem']['type'];
    }

    // Se enviou arquivo
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $conteudoArq = file_get_contents($_FILES['arquivo']['tmp_name']);
        $nomeArq     = $_FILES['arquivo']['name'];
        $tipoArq     = $_FILES['arquivo']['type'];
    }

    // Se enviou capa opcional
    if (isset($_FILES['capa']) && $_FILES['capa']['error'] === UPLOAD_ERR_OK) {
        $conteudoCapa = file_get_contents($_FILES['capa']['tmp_name']);
        $tipoCapa     = $_FILES['capa']['type'];
    }

    // Ajustar a query para incluir capa
    $sql = "INSERT INTO referencial 
                (titulo, mensagem, imagem, tipo_imagem, arquivo, nome_arquivo, tipo_arquivo, capa, tipo_capa, criado_em) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Erro na preparação da query: " . $conn->error);
    }

    // Adaptação: mais 2 parâmetros para capa
    $stmt->bind_param(
        "sssssssss",
        $titulo,
        $mensagem,
        $conteudoImg,
        $tipoImg,
        $conteudoArq,
        $nomeArq,
        $tipoArq,
        $conteudoCapa,
        $tipoCapa
    );

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ Salvo com sucesso!</div>";
    } else {
        echo "<div class='alert alert-error'>❌ Erro ao salvar: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Buscar
$result = $conn->query("SELECT * FROM referencial ORDER BY criado_em DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cursos de formação</title>
    <link rel="stylesheet" href="../content-dados.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
    
   <div class="container">
    <div class="form-container">
        <h1><img src="../semed.png" alt="Logo SEMED" class="logo-semed"></h1>           
        <!-- FORMULÁRIO -->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <h1>Cursos de formação</h1>
                <label for="titulo">Título</label>
                <input type="text" id="titulo" name="titulo" placeholder="Digite o título do evento..." required>
            </div>

            <div class="form-group">
                <label for="imagem">Imagem</label>
                <div class="file-input">
                    <input type="file" id="imagem" name="imagem" accept="image/*">
                    <label for="imagem" class="file-input-label">
                        📎 Clique para selecionar uma imagem
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label for="mensagem">Descrição</label>
                <textarea id="mensagem" name="mensagem" placeholder="Descreva os detalhes do evento..."></textarea>
            </div>
            <button type="submit" class="btn-submit">💾 Salvar</button>
        </form>
    </div>

    <!-- LISTA  -->
<div class="lista">
       <!-- FORMULÁRIO -->
<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="arquivo">Arquivo</label>
        <div class="file-input">
            <input type="file" id="arquivo" name="arquivo" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt">
            <label for="arquivo" class="file-input-label">
                📂 Clique para selecionar um arquivo
            </label>
            <span id="nome-arquivo" class="nome-arquivo"></span>
        </div>
    </div>

    <!-- NOVO CAMPO: Imagem de capa -->
    <div class="form-group">
        <label for="capa">Imagem de Capa (opcional)</label>
        <div class="file-input">
            <input type="file" id="capa" name="capa" accept="image/*">
            <label for="capa" class="file-input-label">
                🖼️ Clique para selecionar uma capa
            </label>
            <span id="nome-capa" class="nome-arquivo"></span>
        </div>
    </div>

    <button type="submit" class="btn-submit">💾 Salvar</button>
</form>

<script>
    const inputArquivo = document.getElementById('arquivo');
    const nomeArquivo = document.getElementById('nome-arquivo');
    const inputCapa = document.getElementById('capa');
    const nomeCapa = document.getElementById('nome-capa');

    inputArquivo.addEventListener('change', function () {
        nomeArquivo.textContent = inputArquivo.files.length > 0 
            ? `📄 ${inputArquivo.files[0].name}` 
            : '';
    });

    inputCapa.addEventListener('change', function () {
        nomeCapa.textContent = inputCapa.files.length > 0 
            ? `🖼️ ${inputCapa.files[0].name}` 
            : '';
    });
</script>

        <br><br>
     <h2>Conteúdo</h2>
    <div class="grid">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="item">
                    <a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Deseja realmente excluir?')" title="Excluir">×</a>
                    
<!-- Exibir CAPA (prioridade máxima) -->
<?php if (!empty($row['capa'])): ?>
    <img src="data:<?php echo $row['tipo_capa']; ?>;base64,<?php echo base64_encode($row['capa']); ?>" alt="Capa do arquivo">

<!-- Senão, exibir IMAGEM comum -->
<?php elseif (!empty($row['imagem'])): ?>
    <img src="data:<?php echo $row['tipo_imagem']; ?>;base64,<?php echo base64_encode($row['imagem']); ?>" alt="Imagem do evento">

<!-- Senão, se houver ARQUIVO, exibe ícone conforme extensão -->
<?php elseif (!empty($row['arquivo'])): ?>
    <div class="arquivos">
        <?php
            $ext = strtolower(pathinfo($row['nome_arquivo'], PATHINFO_EXTENSION));
            $icones = [
                'pdf'  => '../icons/pdf.png',
                'doc'  => '../icons/doc.png',
                'docx' => '../icons/doc.png',
                'xls'  => '../icons/xls.png',
                'xlsx' => '../icons/xls.png',
                'ppt'  => '../icons/ppt.png',
                'pptx' => '../icons/ppt.png',
                'txt'  => '../icons/txt.png'
            ];
            $icone = $icones[$ext] ?? '../icons/file.png';
        ?>
        <div class="arquivo">
            <img src="<?= $icone ?>" alt="<?= $ext ?>">
        </div>
    </div>
<?php endif; ?>

<p><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></p>

<small>
    ✏️ <strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($row['criado_em'])); ?>
</small>
</div>
<?php endwhile; ?>
<?php else: ?>
    <div class="no-items">
        <p>📝 Nenhum conteúdo foi adicionado ainda.</p>
        <p>Utilize o formulário acima para criar o primeiro!</p>
    </div>
<?php endif; ?>
</div>

    <br><br><br><br>
          <!-- Botão flutuante para voltar ao início -->
    <a href="../index.php#index.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>
    <!--footer-->
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>
    <script>
        // Função para melhorar a experiência do usuário
        document.getElementById('imagem').addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label');
            if (e.target.files.length > 0) {
                label.textContent = `📷 ${e.target.files[0].name}`;
                label.style.color = '#667eea';
            } else {
                label.textContent = '📎 Clique para selecionar uma imagem';
                label.style.color = '#6c757d';
            }
        });
        
    </script>
</body>
</html>

<?php $conn->close(); ?>