<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $file = "m3u/" . basename($data['file']);
    
    if (file_exists($file) && unlink($file)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'No se pudo eliminar el archivo']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>