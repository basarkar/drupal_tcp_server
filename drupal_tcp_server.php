<?php 
// by John Schimmel 
// modified from the code at http://www.zend.com/pecl/tutorials/sockets.php
// 
// run this from terminal on mac os x or another command line interface.

require_once('drupal_config.php');

// Set time limit to indefinite execution 
set_time_limit (0); 

// Set the ip and port we will listen on 
$address = '127.0.0.1'; 
$port = 9000; 

// Create a TCP Stream socket 
$sock = socket_create(AF_INET, SOCK_STREAM, 0); 
echo "PHP Socket Server started at " . $address . " " . $port . "\n";

// Bind the socket to an address/port 
socket_bind($sock, $address, $port) or die('Could not bind to address'); 
// Start listening for connections 
socket_listen($sock); 

//loop and listen

while (true) {
    /* Accept incoming requests and handle them as child processes */ 
    $client = socket_accept($sock); 
    
    // Read the input from the client – 1024 bytes 
    $function = socket_read($client, 1024); 
    
    // Strip all white spaces from input 
    $function = ereg_replace("[ \t\n\r]","", $function)."\0"; 

    // display input on server side
    echo "received function: " . $function . "\n";

    $output = drupal_to_js(node_load(1));
   
//    echo "Bappa -----------------> " . $output;
     
    // Display output back to client 
    socket_write($client, $output); 
}

// Close the client (child) socket 
socket_close($client); 

// Close the master sockets 
socket_close($sock); 
