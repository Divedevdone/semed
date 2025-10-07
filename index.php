<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Educação Digital - São José dos Pinhais</title>
  <link rel="stylesheet" href="styles.css">
  <link rel="icon" type="image/png" href="favicon.png">
</head>
<body>
  <header class="header">
    <div class="nav-container">
      <div class="logo"></div>
      <nav>
                <ul class="nav-menu">
                    <?php if (isset($_SESSION["usuario"])): ?>
                        <li><a href="logout.php">Sair (<?= $_SESSION["usuario"] ?>)</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Entrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
    </div>
  </header>  
   <!-- Sidebar já começa visível -->
  <nav class="sidebar show" aria-label="Menu lateral">
    <div class="tab text-hover" data-autor="🤖 RoboEdu:" data-fala="Olá! Pronto para começar?" data-target="index" data-hash="index" data-pasta="index"><span class="text-tab">Início</span></div>
    <div class="tab" data-file="content-estrutura.php" data-autor="🤖 RoboEdu:" data-fala="Diretrizes e protocolos" data-target="estrutura" data-hash="estrutura" data-pasta="estrutura"><span class="text-tab">Núcleo de Educação Digital</span></div>
    <div class="tab" data-file="content-referencial.php" data-autor="🤖 RoboEdu:" data-fala="Orientações pedagógicas" data-target="referencial" data-hash="referencial" data-pasta="referencial"><span class="text-tab">Referencial e documentos</span></div>
    <div class="tab" data-file="content-educacao-digital.php" data-autor="🤖 RoboEdu:" data-fala="Materiais digitais" data-target="educacao-digital" data-hash="educacao-digital" data-pasta="educacao"><span class="text-tab">Educação digital e midiática</span></div>
    <div class="tab" data-file="content-rede.php" data-autor="🤖 RoboEdu:" data-fala="Interligação digital"  data-target="rede" data-hash="rede" data-pasta="rede"><span class="text-tab">Projetos do Núcleo</span></div>
    <div class="tab" data-file="content-recursos.php" data-autor="🤖 RoboEdu:" data-fala="Materiais pedagógicos" data-target="recursos" data-hash="recursos" data-pasta="recursos"><span class="text-tab">Recursos educacionais</span></div>
    <div class="tab" data-file="content-cursos.php" data-autor="🤖 RoboEdu:" data-fala="Certificações" data-target="cursos" data-hash="cursos" data-pasta="cursos"><span class="text-tab">Cursos de formação</span></div>
  </nav>
  <!-- Área principal de conteúdo -->
        <main id="content-area" class="content" role="main" aria-live="polite">
        <section id="index" class="section">
            <h1>Bem-vindo</h1>
            <img src="eduDigital.png" alt="Educação Digital" class="logo-eduDigital">
            <p class="subtitle">Um novo olhar na forma de aprender</p>
        </section>

        <section id="content-estrutura" class="section hidden"></section>
        <section id="content-referencial" class="section hidden"></section>
        <section id="content-educacao-digital" class="section hidden"></section>
        <section id="content-rede" class="section hidden"></section>
        <section id="content-recursos" class="section hidden"></section>
        <section id="content-cursos" class="section hidden"></section>
        <div>
        <img src="roboEdu.png" alt="Logo roboEdu" class="logo-robo">
          <div style="font-size: 0.8rem; margin-top: 0rem;"></div>
        </div>
        <div>
          <img src="semed.png" alt="Logo SEMED" class="logo-semed">
        </div>

    <div class="footer-content">
      <p>SEMED | Secretaria Municipal de Educação</p>
    </div>
  </main>
  <!-- Robô mascote -->
  <div class="robot-mascot" onclick="showMascotMessageBySection()" title="Clique para receber dicas de navegação">
    🤖
  </div>

  <!-- Toggle RoboEdu -->
  <div class="robo-toggle" onclick="toggleRoboEdu()" title="Ligar/Desligar RoboEdu">
    <span id="toggle-icon">🔊</span>
  </div>

  

  <!-- Modal custom alert -->
  <div id="customAlert" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeCustomAlert()">&times;</span>
      <div id="customAlertMessage"></div>
    </div>
  </div>

<script src="script.js"></script>
<script src="script-robo.js"></script>
</body>
</html>
