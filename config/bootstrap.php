<?php
  require_once __DIR__ . '/../vendor/autoload.php';
  require_once __DIR__ . '/../lib/db.php';

  if($APP_CONFIG['environment'] == 'development')
  {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
  }
