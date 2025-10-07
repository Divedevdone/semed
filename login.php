<?php
session_start();
include 'conexao.php'; // conexão com db-semed

$mensagem = "";


// Se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST["usuario"];
    $senha = $_POST["senha"];

    $sql = "SELECT * FROM usuarios WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        if (password_verify($senha, $row["senha"])) {
            $_SESSION["usuario"] = $usuario; // login
            $_SESSION["usuario_id"] = $row["id"]; // id do usuário
            header("Location: index.php");
            exit();
        } else {
            $mensagem = "❌ Senha incorreta!";
        }
    } else {
        $mensagem = "⚠️ Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <style>
        /* Header */
.header {
    background: white;
    padding: 3px;
    position: fixed;
    width: 100%;
    top: 0;
    right:200px
    z-index: 50;
    backdrop-filter: blur(10px);
    color: antiquewhite;
}


        /* Logos posicionados */
.logo-semed {
    position: fixed;
    top: 1px;
    right: 580px;
    width: 210px;
    height: auto;
    z-index: 100;
}
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


/* Media Queries */
@media (min-width: 481px) and (max-width: 768px) { /* celular */
  .logo-semed {
    width: 125px; /* Ajuste do tamanho da logo */
    left: 190px;
  }
  .footer-content {
    font-size: 12px; /* Ajuste do tamanho da fonte */
    top:500px;
    left: 165px;
  }
}

@media (max-width: 480px) { /* tablet */
  .logo-semed {
    width: 70px; /* Ajuste do tamanho da logo */
    top: 10px; /* Ajuste da posição */
    left: 80px;
  }
  .footer-content {
    font-size: 12px; /* Ajuste do tamanho da fonte */
    top:10px;
    left: 420px;
  }
}
    </style>
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="nav-container">
            <nav>
                <ul class="nav-menu">
                    
                </ul>
            </nav>
        </div>
    </header>
    
    <div class="container">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <label for="email">E-mail:</label>
            <input type="email" name="email" id="email" required>
            
            <label for="usuario">Usuário:</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="senha">Senha:</label>
            <input type="password" name="senha" id="senha" required>

            <button type="submit">Entrar</button>
        </form>

        <?php if ($mensagem): ?>
            <div class="mensagem"><?= $mensagem ?></div>
        <?php endif; ?>

        <a href="cadastrar.php" class="link-voltar">Cadastrar</a>
        <br>
        <a href="recuperar.php" class="link-voltar">Esqueci minha senha</a>

    </div>
    
    <div>
                <img src="semed.png" alt="Logo SEMED" class="logo-semed">
                <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
            </div>
            <!-- Botão flutuante para voltar ao início -->
    <a href="index.php" id="backToTop-voltar" class="fab-voltar" aria-label="Voltar">⬅</a>
    <div id="backToTopLabel-voltar" class="fabLabel-voltar">Voltar para início</div>

            <div class="footer-content">
                <p>SEMED | Secretaria municipal de educação</p>
            </div>
</body>
</html>
