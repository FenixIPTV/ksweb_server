<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $oldName = 'm3u/' . basename($data['oldName']);
    $newName = 'm3u/' . basename($data['newName']) . '.m3u'; // Asegurar extensión .m3u

    if (!file_exists($oldName)) {
        echo json_encode(['error' => 'Archivo original no encontrado']);
        exit;
    }

    if (rename($oldName, $newName)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Error al renombrar el archivo']);
    }
} else {
    echo json_encode(['error' => 'Método no permitido']);
}
?>