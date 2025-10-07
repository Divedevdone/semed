<?php
session_start();
include 'conexao.php'; // conexão com db-semed


$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];

    $sql = "SELECT * FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $token = bin2hex(random_bytes(50)); // token seguro
        $expira = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $sql = "UPDATE usuarios SET token_recuperacao=?, token_expira=? WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $token, $expira, $email);
        $stmt->execute();

        // Enviar email (precisa configurar servidor de email)
       $link = "http://localhost/semed6/resetar.php?token=" . urlencode($token);
        mail($email, "Recuperação de Senha", "Clique aqui para redefinir sua senha: $link");

        $mensagem = "📧 Um link de redefinição foi enviado para seu e-mail!";
    } else {
        $mensagem = "⚠️ E-mail não encontrado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
     <style>
        /* === INÍCIO CSS === */
        /* Header */
        .header {
            background: white;
            padding: 3px;
            position: fixed;
            width: 100%;
            top: 0;
            right:200px;
            z-index: 50;
            backdrop-filter: blur(10px);
            color: antiquewhite;
        }
        .logo-semed {
            position: fixed;
            top: 50px;
            right: 520px;
            width: 325px;
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
    width: 350px;
    height: auto; /* ou height: 100px; sem o ":" final */
    padding: 20px 30px;
    border-radius: 10px;
    box-shadow: 0 0 25px rgba(0,0,0,0.2);
    background: white;
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
button {
    margin-top: 15px!important;
    width: 100%!important;
    padding: 10px;
    border: none;
    background: #42519C;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    cursor: pointer;
    align-items: center!important;
}
label{
    text-align:center;
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
        .link-voltar {
            display: block;
            text-align: center;
            margin-top: 15px;
            text-decoration: none;
            color: #42519C;
        }
        /* Media Queries */
        @media (min-width: 481px) and (max-width: 768px) {
            .logo-semed { width: 125px; left: 190px; }
            .footer-content { font-size: 12px; top:500px; left: 165px; }
        }
        @media (max-width: 480px) {
            .logo-semed { width: 80px; top: 10px; left: 80px; }
            .footer-content { font-size: 12px; top:10px; left: 420px; }
        }
    </style>
    <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
    
    <form method="post">
        <h2>Recuperar Senha</h2>
        <label for="email">Digite seu e-mail cadastrado:</label>
        <input type="email" name="email" required>
        <button type="submit" class="button">Enviar link</button>
    </form>
    <p><?= $mensagem ?></p>
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