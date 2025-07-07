<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $filename = 'm3u/' . basename($data['file']);
    $channels = $data['channels'];
    
    $m3uContent = "#EXTM3U\n";
    foreach ($channels as $channel) {
        $m3uContent .= "#EXTINF:-1,{$channel['name']}\n{$channel['url']}\n";
    }
    
    if (file_put_contents($filename, $m3uContent) !== false) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error al guardar el archivo']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>