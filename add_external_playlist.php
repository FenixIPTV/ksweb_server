<?php
require_once 'auth.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['url']) || !isset($data['type'])) {
    http_response_code(400);
    die(json_encode(['error' => 'Datos incompletos']));
}

// Leer playlists existentes
$playlists = [];
if (file_exists('external_playlists.json')) {
    $playlists = json_decode(file_get_contents('external_playlists.json'), true);
    if (!is_array($playlists)) {
        $playlists = [];
    }
}

// Verificar si ya existe
foreach ($playlists as $playlist) {
    if ($playlist['name'] === $data['name']) {
        http_response_code(409);
        die(json_encode(['error' => 'Ya existe una playlist con este nombre']));
    }
}

// Agregar nueva playlist
$playlists[] = [
    'name' => $data['name'],
    'url' => $data['url'],
    'type' => $data['type'],
    'username' => $data['username'] ?? '',
    'password' => $data['password'] ?? '',
    'added_at' => date('Y-m-d H:i:s')
];

// Guardar en archivo JSON
if (file_put_contents('external_playlists.json', json_encode($playlists, JSON_PRETTY_PRINT)) === false) {
    http_response_code(500);
    die(json_encode(['error' => 'Error al guardar la playlist']));
}

echo json_encode(['success' => true, 'playlist' => $data['name']]);
?>