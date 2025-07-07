<?php
// Configuración de Google Drive
define('GOOGLE_DRIVE_API_KEY', 'TU_API_KEY');
define('GOOGLE_DRIVE_CLIENT_ID', 'TU_CLIENT_ID.apps.googleusercontent.com');

// Configuración del servidor
define('MAX_CONNECTIONS', 50);

function getServerInfo($type) {
    switch($type) {
        case 'connections':
            return rand(1, MAX_CONNECTIONS) . '/' . MAX_CONNECTIONS;
        case 'memory':
            return round(memory_get_usage(true)/1024/1024, 2) . ' MB';
        default:
            return 'N/A';
    }
}
?>