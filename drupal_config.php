<?php
$current_dir = getcwd();
$drupal_path = "/var/www/html/d6";
chdir($drupal_path);
$_SERVER['HTTP_HOST'] = 'example.org';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
define('DRUPAL_ROOT',$drupal_path);

// Bootstrap.
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

global $user;
$user = user_load(1);
