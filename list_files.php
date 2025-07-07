<?php
require_once 'auth.php';
header('Content-Type: application/json');

$localFiles = glob('m3u/*.m3u');
$playlists = [];

foreach ($localFiles as $file) {
    $playlists[] = [
        'name' => basename($file, '.m3u'),
        'type' => 'local',
        'path' => $file,
        'size' => filesize($file),
        'modified' => date('Y-m-d H:i:s', filemtime($file))
    ];
}

// Agregar playlists externas si existen
if (file_exists('external_playlists.json')) {
    $externals = json_decode(file_get_contents('external_playlists.json'), true);
    if (is_array($externals)) {
        foreach ($externals as $external) {
            $playlists[] = [
                'name' => $external['name'],
                'type' => $external['type'],
                'url' => $external['url'],
                'username' => $external['username'] ?? '',
                'added_at' => $external['added_at'] ?? date('Y-m-d H:i:s')
            ];
        }
    }
}

echo json_encode($playlists);
?>