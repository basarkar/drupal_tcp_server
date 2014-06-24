<?php

require_once('drupal_client.php');
$link = drupal_client_connect();

$function = 'node_load';
$argument = array(1);

$result = drupal_client_result($link, $function, $argument);

print_R($result);

//print_R(drupal_client_result($link, 'user_load', array(1)));

drupal_client_close($link);
