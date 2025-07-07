<?php
$filePath = "m3u/" . basename($_GET['file']); // Previene directory traversal

if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'm3u') {
    header('Content-Type: audio/x-mpegurl');
    readfile($filePath);
} else {
    http_response_code(404);
    echo "Archivo no encontrado";
}
?>