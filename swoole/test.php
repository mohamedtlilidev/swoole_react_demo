<?php
header('Content-Type: application/json');

$status = [
    'apache' => 'running',
    'websocket_port' => 8080,
    'websocket_url' => 'ws://' . $_SERVER['HTTP_HOST'] . ':8082/chat'
];

// Test if WebSocket port is listening
$connection = @fsockopen('localhost', 8080, $errno, $errstr, 1);
if ($connection) {
    $status['websocket_status'] = 'listening';
    fclose($connection);
} else {
    $status['websocket_status'] = 'not listening';
    $status['error'] = $errstr;
}

echo json_encode($status, JSON_PRETTY_PRINT);