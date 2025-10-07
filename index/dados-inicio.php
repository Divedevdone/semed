<?php
include __DIR__ . '/../conexao.php'; // conexão com db-semed

// --- EXCLUSÃO DE CRONOGRAMA ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    $sql = "DELETE FROM cronograma WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redireciona para evitar "refresh duplicado"
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<p style='color:red'>Erro ao excluir: " . $conn->error . "</p>";
    }
}

// --- EXCLUSÃO DE OBSERVAÇÃO ---
if (isset($_GET['delete_obs'])) {
    $id = intval($_GET['delete_obs']);

    $sql = "DELETE FROM observacao WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $stmt->close();
            header("Location: ".$_SERVER['PHP_SELF']);
            exit;
        } else {
            echo "<p style='color:red'>Erro ao excluir observação: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p style='color:red'>Erro na preparação da query de exclusão: " . $conn->error . "</p>";
    }
}

// --- PROCESSAMENTO DO FORMULÁRIO DE OBSERVAÇÃO ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['salvar_observacao_simples'])) {
    $observacao = trim($_POST['observacao_simples']);
    
    if (!empty($observacao)) {
        $sql = "INSERT INTO observacao (observacao, criado_em) VALUES (?, NOW())";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $observacao);
            
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>✅ Observação salva com sucesso!</div>";
            } else {
                echo "<div class='alert alert-error'>❌ Erro ao salvar observação: " . $stmt->error . "</div>";
            }
            $stmt->close();
        } else {
            echo "<div class='alert alert-error'>❌ Erro na preparação da query: " . $conn->error . "</div>";
        }
    }
}

// --- PROCESSAMENTO DO FORMULÁRIO DE CRONOGRAMA ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['salvar_observacao_simples'])) {
    $titulo   = $_POST['titulo'];
    $mensagem = $_POST['mensagem'];
    $data     = $_POST['data_evento'];
    $hora     = $_POST['hora_evento'];

    // Verifica se uma imagem foi enviada
    if ($_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $nomeTemp = $_FILES['imagem']['tmp_name'];
        $conteudo = file_get_contents($nomeTemp);
        $tipo     = $_FILES['imagem']['type'];

        $sql = "INSERT INTO cronograma (titulo, mensagem, data_evento, hora_evento, imagem, tipo_imagem, criado_em) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("ssssss", $titulo, $mensagem, $data, $hora, $conteudo, $tipo);
    } else {
        // Sem imagem
        $sql = "INSERT INTO cronograma (titulo, mensagem, data_evento, hora_evento, criado_em) 
                VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Erro na preparação da query: " . $conn->error);
        }

        $stmt->bind_param("ssss", $titulo, $mensagem, $data, $hora);
    }

    // Executa a query
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>✅ Cronograma salvo com sucesso!</div>";
    } else {
        echo "<div class='alert alert-error'>❌ Erro ao salvar: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

// Buscar todas as observações
$observacoes = $conn->query("SELECT * FROM observacao ORDER BY criado_em DESC");

// Buscar todos os cronogramas
$result = $conn->query("SELECT * FROM cronograma ORDER BY criado_em DESC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma</title>
    <link rel="stylesheet" href="content-inicio.css">
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
    
    <div class="container">
        <div class="form-container">
            <h1><img src="../semed.png" alt="Logo SEMED" class="logo-semed"></h1>
            
            <!-- FORMULÁRIO DE OBSERVAÇÃO -->
            <div class="form-container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="observacao_simples">Avisos:</label>
                        <textarea id="observacao_simples" name="observacao_simples" placeholder="Digite um aviso..." required></textarea>
                    </div>
                    <button type="submit" name="salvar_observacao_simples" class="btn-submit">Adicionar aviso</button>
                </form>
            </div>
            
            <br><br>
            
            <!-- FORMULÁRIO DE CRONOGRAMA -->
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group"><h1>Cronograma</h1>
                    <label for="titulo">Título</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Digite o título..." required>
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
                    <textarea id="mensagem" name="mensagem" placeholder="Descreva os detalhes..."></textarea>
                </div>

                <div class="form-group">
                    <label for="data_evento">Data</label>
                    <input type="date" id="data_evento" name="data_evento" required>
                </div>

                <div class="form-group">
                    <label for="hora_evento">Horário</label>
                    <input type="time" id="hora_evento" name="hora_evento" required>
                </div>

                <button type="submit" class="btn-submit">💾 Salvar cronograma</button>
            </form>
        </div>

        <!-- LISTA DE OBSERVAÇÕES -->
        <div class="lista">
            <h2>📢 Avisos</h2>
            <div class="grid">
                <?php if ($observacoes && $observacoes->num_rows > 0): ?>
                    <?php while($obs = $observacoes->fetch_assoc()): ?>
                        <div class="item observacao-item">
                            <a class="delete" href="?delete_obs=<?php echo $obs['id']; ?>" onclick="return confirm('Deseja realmente excluir esta observação?')" title="Excluir">×</a>
                            <div class="observacao-conteudo">
                                <p><?php echo nl2br(htmlspecialchars($obs['observacao'])); ?></p>
                            </div>
                            <small>
                                ✏️ <strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($obs['criado_em'])); ?>
                            </small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-items">
                        <p>📢 Nenhuma observação foi adicionada ainda.</p>
                        <p>Utilize o formulário acima para criar a primeira!</p>
                    </div>
                <?php endif; ?>
            </div>
        

        <!-- LISTA DE CRONOGRAMAS -->
        <div class="lista">
            <h2>📋 Cronogramas</h2>
            <div class="grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="item">
                            <a class="delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Deseja realmente excluir este cronograma?')" title="Excluir">×</a>
                            <h3><?php echo htmlspecialchars($row['titulo']); ?></h3>
                           
                            <?php if (!empty($row['imagem'])): ?>
                            <img src="data:<?php echo $row['tipo_imagem']; ?>;base64,<?php echo base64_encode($row['imagem']); ?>" alt="Imagem do evento">
                            <?php endif; ?>

                            <p><?php echo nl2br(htmlspecialchars($row['mensagem'])); ?></p>
                            <small>
                                📅 <strong>Data:</strong> <?php echo date('d/m/Y', strtotime($row['data_evento'])); ?><br>
                                🕐 <strong>Horário:</strong> <?php echo $row['hora_evento']; ?><br>
                                ✏️ <strong>Criado em:</strong> <?php echo date('d/m/Y H:i', strtotime($row['criado_em'])); ?>
                            </small>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="no-items">
                        <p>📝 Nenhum cronograma foi adicionado ainda.</p>
                        <p>Utilize o formulário acima para criar o primeiro!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    
</body>
</html>
    <br><br><br><br>
          <!-- Botão flutuante para voltar ao início -->
    <a href="../index.php#educacao.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>
    <!--footer-->
    <div class="footer-content">
        <p>SEMED | Secretaria Municipal de Educação</p>
    </div>

    <!-- Botão de edição fixo -->
    <?php if (isset($_SESSION["usuario"])): ?>
    <div class="btn-add" onclick="addDataInicio()" data-autor="🤖 RoboEdu:" data-fala="Editar" title="Editar">
        <span id="btn-icon">✏️</span>
    </div>
    <input type="file" id="hiddenUpload" style="display:none" />
    <?php endif; ?>

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
        

        // Função placeholder para addDataInicio (substitua pela sua implementação)
        function addDataInicio() {
            console.log('Função de edição chamada');
            // Implementar sua lógica de edição aqui
        }
    </script>
</body>
</html>

<?php $conn->close(); ?>