<?php
require_once 'auth.php';
header('Content-Type: application/json');

$file = isset($_GET['file']) ? basename($_GET['file']) : '';
$url = isset($_GET['url']) ? $_GET['url'] : '';

if (!empty($file)) {
    $filePath = "m3u/" . $file;
    
    if (!file_exists($filePath)) {
        http_response_code(404);
        echo json_encode(['error' => 'Archivo no encontrado']);
        exit;
    }
    
    $content = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
} elseif (!empty($url)) {
    // Para playlists externas, podrías implementar la descarga aquí
    $content = @file($url, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($content === false) {
        http_response_code(400);
        echo json_encode(['error' => 'No se pudo cargar la URL externa']);
        exit;
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetro file o url requerido']);
    exit;
}

$channels = [];
$currentChannel = null;

foreach ($content as $line) {
    $line = trim($line);
    
    if (strpos($line, '#EXTINF:') === 0) {
        $parts = explode(',', $line, 2);
        $currentChannel = [
            'name' => isset($parts[1]) ? trim($parts[1]) : 'Canal sin nombre',
            'url' => ''
        ];
    } elseif ($currentChannel !== null && !empty($line) && $line[0] !== '#') {
        $currentChannel['url'] = trim($line);
        $channels[] = $currentChannel;
        $currentChannel = null;
    }
}

echo json_encode($channels);
?>