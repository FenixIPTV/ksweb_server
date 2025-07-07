<?php
require_once 'auth.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die(json_encode(['error' => 'Método no permitido']));
}

if (!isset($_POST['name']) || empty($_POST['name'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Nombre de playlist requerido']));
}

if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    http_response_code(400);
    die(json_encode(['error' => 'Archivo M3U requerido']));
}

$targetDir = 'm3u/';
$filename = preg_replace('/[^a-zA-Z0-9_-]/', '', $_POST['name']) . '.m3u';
$targetFile = $targetDir . $filename;

// Validar contenido M3U básico
$content = file_get_contents($_FILES['file']['tmp_name']);
if (strpos($content, '#EXTM3U') === false) {
    http_response_code(400);
    die(json_encode(['error' => 'El archivo no es un M3U válido (falta #EXTM3U)']));
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
    echo json_encode([
        'success' => true, 
        'filename' => $filename,
        'path' => $targetFile,
        'size' => filesize($targetFile)
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Error al mover el archivo subido']);
}
?>