<?php
include 'conexao.php';
$mensagem = "";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $sql = "SELECT * FROM usuarios WHERE token_recuperacao=? AND token_expira > NOW()";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $novaSenha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

            $sql = "UPDATE usuarios SET senha=?, token_recuperacao=NULL, token_expira=NULL WHERE token_recuperacao=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $novaSenha, $token);
            $stmt->execute();

            $mensagem = "✅ Senha alterada com sucesso! <a href='login.php'>Entrar</a>";
        }
    } else {
        $mensagem = "❌ Link inválido ou expirado!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Redefinir Senha</title>
    <style>
        /* Reutiliza exatamente o mesmo CSS da página recuperar.php */
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
        .logo-semed { position: fixed; top: 1px; right: 520px; width: 325px; height: auto; z-index: 100; }
        .footer-content { position: absolute; top: 532px; right: 335px; text-align: center; font-size: 14px; width: 35%; display: flex; }
        body { font-family: Arial, sans-serif; background: white; display: flex; justify-content: center; align-items: center; height: 90vh; }
        .container { background: white; padding: 20px 30px; border-radius: 10px; box-shadow: 0 0 25px rgba(0,0,0,0.2); width: 350px; }
        h2 { text-align: center; color: #333; }
        label { font-weight: bold; margin-top: 10px; display: block; }
        input { width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc; }
        button { margin-top: 15px; width: 100%; padding: 10px; border: none; background: #42519C; color: white; font-weight: bold; border-radius: 5px; cursor: pointer; }
        button:hover { background: #2e3973; }
        .mensagem { text-align: center; margin-top: 10px; font-weight: bold; }
        .link-voltar { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #42519C; }
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
       
       
        @media (min-width: 481px) and (max-width: 768px) { .logo-semed { width: 125px; left: 190px; } .footer-content { font-size: 12px; top:500px; left: 165px; } }
        @media (max-width: 480px) { .logo-semed { width: 80px; top: 10px; left: 80px; } .footer-content { font-size: 12px; top:10px; left: 420px; } }
    
    
    </style>
    <link rel="icon" type="image/png" href="../favicon.png">
</head>
<body>
    
    <?php if ($mensagem): ?>
        <p><?= $mensagem ?></p>
    <?php else: ?>
        <form method="post">
            <h2>Redefinir Senha</h2>
            <label>Nova Senha:</label>
            <input type="password" name="senha" required>
            <button type="submit">Salvar</button>
        </form>
    <?php endif; ?>
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
