<?php
$current_dir = getcwd();
$drupal_path = "/var/www/html/d6";
chdir($drupal_path);
require_once './includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


