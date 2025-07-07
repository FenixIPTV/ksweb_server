<?php
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['m3uFile'])) {
    $targetDir = 'm3u/';
    $filename = basename($_FILES['m3uFile']['name']);
    $targetFile = $targetDir . $filename;

    // Validar extensión
    if (pathinfo($filename, PATHINFO_EXTENSION) !== 'm3u') {
        $response['error'] = 'Solo se permiten archivos .m3u';
        echo json_encode($response);
        exit;
    }

    // Validar contenido básico
    $content = file_get_contents($_FILES['m3uFile']['tmp_name']);
    if (strpos($content, '#EXTM3U') === false) {
        $response['error'] = 'El archivo no tiene un formato M3U válido';
        echo json_encode($response);
        exit;
    }

    // Mover archivo
    if (move_uploaded_file($_FILES['m3uFile']['tmp_name'], $targetFile)) {
        $response['success'] = true;
        $response['filename'] = $filename;
    } else {
        $response['error'] = 'Error al mover el archivo';
    }
} else {
    $response['error'] = 'No se recibió ningún archivo';
}

echo json_encode($response);
?>