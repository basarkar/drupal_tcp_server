<?php

class MySocketServer
{
    protected $socket;
    protected $clients = array();
    protected $changed;
    
    function __construct($host = 'localhost', $port = 9000)
    {
        set_time_limit(0);
        ob_implicit_flush();
        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

        //bind socket to specified host
        socket_bind($socket, 0, $port);
        //listen to port
        socket_listen($socket, 5);
        $this->socket = $socket;
    }
    
    function __destruct()
    {
        foreach($this->clients as $client) {
            socket_close($client);
        }
        socket_close($this->socket);
    }
    
    function run()
    {
        while(true) {
            if ($this->waitForChange() < 1) {
              continue;
            }
            $this->checkNewClients();
            $this->checkMessageRecieved();
        }
    }
    
    function checkMessageRecieved()
    {
        foreach ($this->clients as $key => $socket) {
          if (in_array($socket, $this->changed)) {
            $buffer = @socket_read($socket, 2048, PHP_NORMAL_READ);
            socket_getpeername($socket, $ip);
            $time = date(DATE_ATOM, time());
            if ($buffer === FALSE) {
              unset($this->clients[$key]);
              echo "$ip\tconnection closed\t$time\n";
              continue;
            }
            $buffer = ereg_replace("[\n\r]", "", $buffer);
            echo "$ip\tNew message received : $buffer\t$time\n";
            $buffer = json_decode($buffer);
            extract((array)$buffer);
            // Call Drupal function.
            $output = call_user_func_array($function, $argument);
            $talkback = drupal_to_js($output);
            $this->sendMessage($socket, $talkback);
          }
        }
    }
    
    function waitForChange()
    {
        //reset changed
        $this->changed = array_merge(array($this->socket), $this->clients);
        //variable call time pass by reference req of socket_select
        $null = null;
        //this next part is blocking so that we dont run away with cpu
        return socket_select($this->changed, $null, $null, $tv_sec = 5);
    }
    
    function checkNewClients()
    {
        if (!in_array($this->socket, $this->changed)) {
            return; //no new clients
        }
        $socket_new = socket_accept($this->socket); //accept new socket
        $this->clients[] = $socket_new;
        socket_getpeername($socket_new, $ip);
        $time = date(DATE_ATOM, time()); 
        echo "$ip\tNew client connected\t$time\n";
    }
    
    function sendMessage($client, $msg)
    {
      @socket_write($client,$msg,strlen($msg));
      return true;
    }
}

require_once(dirname(__FILE__) . '/drupal_config.php');

$myServer = new MySocketServer();
$myServer->run();
