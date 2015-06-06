<?php

  require_once __DIR__ . '/../config/bootstrap.php';

  ToroHook::add("404",  function() {
    echo "404";
  });

  Toro::serve(array(
      "/police/login" => "PoliceLoginController",
      "/citizen/login" => "PoliceLoginController",
      "/citizen/status_fcir" => "ForwardedCIRStatusController",
      "/police/dashboard" => "PoliceDashboardController"
    ));
