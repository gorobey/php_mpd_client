<?php

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    exit;
}

$status = socket_connect($socket, '127.0.0.1', 6600);
if ($status === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    exit;
}

echo socket_read($socket, 4096);
socket_write($socket, "command_list_begin
volume 86
play 2
status
command_list_end
");
echo socket_read($socket, 4096);
socket_close($socket);
