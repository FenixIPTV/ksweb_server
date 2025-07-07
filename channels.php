<?php
header('Content-Type: application/json');

$dir = 'm3u/';
$files = glob($dir . '*.m3u');
$channels = [];

foreach ($files as $file) {
    $content = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $currentChannel = null;
    
    foreach ($content as $line) {
        $line = trim($line);
        if (strpos($line, '#EXTINF:') === 0) {
            $currentChannel = [
                'name' => trim(explode(',', $line, 2)[1] ?? 'Canal sin nombre'),
                'file' => basename($file),
                'url' => ''
            ];
        } elseif ($currentChannel && !empty($line) && $line[0] !== '#') {
            $currentChannel['url'] = $line;
            $channels[] = $currentChannel;
            $currentChannel = null;
        }
    }
}

echo json_encode($channels);
?>