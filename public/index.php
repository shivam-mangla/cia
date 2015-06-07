<?php

  require_once __DIR__ . '/../config/bootstrap.php';

  ToroHook::add("404",  function() {
    echo "404";
  });

  Toro::serve(array(
      "/police/login" => "PoliceLoginController",
      "/police/dashboard" => "PoliceDashboardController",
      "/police/requests" => "PoliceRequestsController",
      "/police/requests/:number" => "PoliceSingleRequestController",
      "/police/reports/open" => "PoliceOpenReportsController",
      "/police/reports/in_process" => "PoliceInprocessReportsController",
      "/police/reports/review" => "PoliceReviewReportController",
      "/police/reports/:number/process" => "PoliceProcessReportController",
      "/police/reports/:number/send_for_review" => "PoliceReportForReviewController",
      "/citizen/login" => "PoliceLoginController",
      "/citizen/status_fcir" => "ForwardedCIRStatusController"
    ));
