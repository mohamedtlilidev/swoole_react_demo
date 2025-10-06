<?php
require __DIR__ . '/vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

class ChatServer implements MessageComponentInterface {
    public function onOpen(ConnectionInterface $conn) {
        echo "New connection: {$conn->resourceId}\n";
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        echo "Message from {$from->resourceId}: $msg\n";
        $from->send("Echo: $msg");
    }
    public function onClose(ConnectionInterface $conn) {
        echo "Connection {$conn->resourceId} closed\n";
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatServer()
        )
    ),
    8084
);

echo "WebSocket server running at ws://localhost:8084\n";
$server->run();
