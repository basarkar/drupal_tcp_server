<?php

require_once('drupal_client.php');
$link = drupal_client_connect();

$function = "node_load\n";
$argument = array(1);

$result = drupal_client_result($link, $function, $argument);

print_R($result);

//$result = drupal_client_result($link, "user_load\n", array(1));

//print_R($result);

drupal_client_close($link);
