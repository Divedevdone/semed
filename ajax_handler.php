

<?php
if (!isset($_GET['pasta']) || !isset($_GET['target'])) {
    http_response_code(400);
    echo "Parâmetros inválidos.";
    exit;
}

$pasta = basename($_GET['pasta']);     // ex: estrutura, referencial, educacao
$target = basename($_GET['target']);   // ex: estrutura, referencial, educacao

$pastasPermitidas = ['index', 'estrutura', 'referencial', 'educacao', 'rede', 'recursos', 'cursos'];
if (!in_array($pasta, $pastasPermitidas)) {
    http_response_code(403);
    echo "Pasta não permitida.";
    exit;
}

$file = __DIR__ . "/$pasta/content-$target.php";

if (file_exists($file)) {
    include $file;
} else {
    http_response_code(404);
    echo "Conteúdo não encontrado.";
}
