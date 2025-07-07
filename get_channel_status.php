<?php
header('Content-Type: application/json');

$statusFiles = glob('m3u/*_results.json');
$statusData = [];

foreach ($statusFiles as $file) {
    $playlistName = str_replace('_results.json', '', basename($file));
    $content = file_get_contents($file);
    $channels = json_decode($content, true);
    
    if (is_array($channels)) {
        $statusData[$playlistName] = $channels;
    }
}

echo json_encode($statusData);
?>