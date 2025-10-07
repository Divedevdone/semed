<?php
session_start();
include 'conexao.php';

$mensagem = "";

// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST["usuario"]);
    $email   = trim($_POST["email"]);
    $senha   = trim($_POST["senha"]);

    if (!empty($usuario) && !empty($email) && !empty($senha)) {
        // Gera hash seguro da senha
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        // Insere no banco
        $sql = "INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $usuario, $email, $senhaHash);

        if ($stmt->execute()) {
            $mensagem = "✅ Usuário cadastrado com sucesso!";
        } else {
            if ($conn->errno == 1062) { // erro de chave única (usuário já existe ou email já existe)
                $mensagem = "⚠️ Usuário ou e-mail já cadastrado!";
            } else {
                $mensagem = "❌ Erro ao cadastrar: " . $conn->error;
            }
        }
    } else {
        $mensagem = "⚠️ Preencha todos os campos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Usuário</title>
    <style>
        /* Logos posicionados */
.logo-semed {
    position: fixed;
    top: 1px;
    right: 540px;
    width: 280px;
    height: auto;
    z-index: 100;
}
    
        body {
            font-family: Arial, sans-serif;
            background: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }
        .container {
            background: white;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0,0,0,0.2);
            width: 350px;
            height:100px:
        }
        .cadastro {
            background: white;
            width: 100px;
     
        }
        h2 {
            text-align: center;
            color: #333;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            display: block;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            border: none;
            background: #42519C;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background: #2e3973;
        }
        .mensagem {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
        }

         .fab-voltar {
    position: fixed;
    left: 24px;
    bottom: 24px;
    width: 64px;
    height: 64px;
    border-radius: 50%;
    background: linear-gradient(180deg, #1976d2, #115293);
    box-shadow: 0 8px 20px rgba(3, 64, 120, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #fff;
    font-size: 28px;
    text-decoration: none;
    z-index: 9999;
    transition: transform .12s ease, box-shadow .12s ease;
}

.fab-voltar:active {
    transform: scale(.96);
}

.fab-voltar:hover {
    box-shadow: 0 12px 30px rgba(3, 64, 120, 0.28);
}

/* Rótulo do botão "Voltar para início" */
.fabLabel-voltar {
    position: fixed;
    bottom: 90px;
    left: 24px;
    background-color: #333;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    display: none;
    z-index: 1000;
}

        .link-voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #42519C;
        }
        /* Rodapé */
/* Rodapé */
.footer-content {
    position: absolute;
    top: 532px;
    right: 335px;
    text-align: center;
    font-size: 14px;
    width: 35%;
    display: flex;
}
    

/* Media Queries */
@media (min-width: 481px) and (max-width: 768px) { /* celular */
  .logo-semed {
    width: 125px; /* Ajuste do tamanho da logo */
    left: 190px;
  }
  .footer-content {
    font-size: 16px; /* Ajuste do tamanho da fonte */
    top:500px;
    left: 165px;
  }
}

@media (max-width: 480px) { /* tablet */
  .logo-semed {
    width: 80px; /* Ajuste do tamanho da logo */
    top: 10px; /* Ajuste da posição */
    left: 80px;
  }
  .footer-content {
    font-size: 14px; /* Ajuste do tamanho da fonte */
    top:10px;
    left: 420px;
  }
}
    </style>
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    <div class="container">
        <h2>Novo Usuário</h2>
        <form action="cadastrar.php" method="post">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>

            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Cadastrar</button>
        </form>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>
    </div>
    

            <div>
                <img src="semed.png" alt="Logo SEMED" class="logo-semed">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>

            <!-- Botão flutuante para voltar ao início -->
    <a href="login.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>

            <div class="footer-content">
                <p>SEMED | Secretaria municipal de educação</p>
            </div>
</body>
</html>
