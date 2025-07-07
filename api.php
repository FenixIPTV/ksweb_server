<?php
require_once 'auth.php';
header('Content-Type: application/json');

// Autenticación API Key alternativa
if (!isset($_GET['apikey']) && !isset($_SESSION['authenticated'])) {
    header('HTTP/1.1 401 Unauthorized');
    die(json_encode(['error' => 'API key requerida']));
}

// Configuración API
$api_config = [
    'api_keys' => [
        '12345' => ['access' => 'read'],
        '67890' => ['access' => 'full']
    ],
    'rate_limit' => 100 // peticiones por hora
];

// Verificar API Key si se proporciona
if (isset($_GET['apikey'])) {
    if (!isset($api_config['api_keys'][$_GET['apikey']])) {
        header('HTTP/1.1 403 Forbidden');
        die(json_encode(['error' => 'API key inválida']));
    }
    $access_level = $api_config['api_keys'][$_GET['apikey']]['access'];
}

// Endpoints
$endpoint = $_GET['action'] ?? '';

switch ($endpoint) {
    case 'list_playlists':
        $files = glob('m3u/*.m3u');
        $playlists = array_map('basename', $files);
        echo json_encode($playlists);
        break;
        
    case 'get_playlist':
        if (!isset($_GET['file'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Parámetro file requerido']);
            break;
        }
        $file = 'm3u/' . basename($_GET['file']);
        if (file_exists($file)) {
            readfile($file);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Archivo no encontrado']);
        }
        break;
        
    case 'add_channel':
        if ($access_level !== 'full') {
            http_response_code(403);
            echo json_encode(['error' => 'Acceso no autorizado']);
            break;
        }
        // Implementar lógica para agregar canal
        break;
        
    default:
        http_response_code(400);
        echo json_encode([
            'error' => 'Acción no válida',
            'endpoints_disponibles' => [
                'list_playlists',
                'get_playlist',
                'add_channel'
            ]
        ]);
}
?>