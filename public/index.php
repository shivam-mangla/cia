<?php

  require_once __DIR__ . '/../config/bootstrap.php';

  ToroHook::add("404",  function() {
    echo "404";
  });

  Toro::serve(array(
      "/" => "HomeController",
      "/police/login" => "PoliceLoginController",
      "/police/dashboard" => "PoliceDashboardController",
      "/police/requests" => "PoliceRequestsController",
      "/police/requests/:number" => "PoliceSingleRequestController",
      "/police/reports/open" => "PoliceOpenReportsController",
      "/police/reports/in_process" => "PoliceInprocessReportsController",
      "/police/reports/review" => "PoliceReviewListController",
      "/police/reports/:number" => "PoliceReviewReportController",
      "/police/reports/:number/process" => "PoliceProcessReportController",
      "/police/reports/:number/send_for_review" => "PoliceReportForReviewController",
      "/police/reports/:number/mark_verified" => "PoliceReportVerificationController",
      "/citizen/login" => "CitizenLoginController",
      "/citizen/dashboard" => "CitizenDashboardController",
      "/citizen/status_fcir" => "ForwardedCIRStatusController"
    ));
