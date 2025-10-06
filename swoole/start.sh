#!/bin/bash
set -e

echo "Starting Apache..."
service apache2 start

echo "Waiting for Apache to start..."
sleep 3

echo "Starting WebSocket server on port 8084..."
cd /var/www/html
php server.php > /var/log/websocket.log 2>&1 &

echo "WebSocket server started (PID: $!)"
echo "Logs available at /var/log/websocket.log"

# Keep container running and show logs
tail -f /var/log/apache2/access.log /var/log/apache2/error.log /var/log/websocket.log