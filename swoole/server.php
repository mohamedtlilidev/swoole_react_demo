<?php
use Swoole\WebSocket\Server;

$server = new Server("0.0.0.0", 8085);

$server->on("start", function (Server $server) {
    echo "Swoole WebSocket server with coroutines started at ws://localhost:8085\n";
});

$server->on("open", function (Server $server, $request) {
    echo "Connection opened: {$request->fd}\n";
    $server->push($request->fd, "Welcome! Your client ID is {$request->fd}");
});

$server->on("message", function (Server $server, $frame) {
    echo "Message from {$frame->fd}: {$frame->data}\n";

    // Example coroutine: simulate async work
    Swoole\Coroutine::create(function () use ($server, $frame) {
        // Simulate some asynchronous task (e.g., API call, DB query)
        Swoole\Coroutine::sleep(2); // non-blocking sleep
        $server->push($frame->fd, "Processed your message asynchronously: {$frame->data}");
    });

    // Broadcast immediately to all clients
    foreach ($server->connections as $fd) {
        if ($server->isEstablished($fd)) {
            $server->push($fd, "Client {$frame->fd} says: {$frame->data}");
        }
    }
});

$server->on("close", function ($server, $fd) {
    echo "Connection closed: {$fd}\n";
});

$server->start();
