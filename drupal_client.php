<?php
function drupal_client_result($socket, $function, $argument) {
$message = $function;
// send string to server
socket_write($socket, $message, strlen($message)) or die("Could not send data to server\n");
// get server response
$result = socket_read($socket, 1024) or die("Could not read server response\n");

return json_decode($result);
}

function drupal_client_connect() {
  $host    = "127.0.0.1";
  $port    = 9000;
  $socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");
  $result = socket_connect($socket, $host, $port) or die("Could not connect to server\n");
  return $socket;
}

function drupal_client_close($socket) {
  socket_close($socket);
}
