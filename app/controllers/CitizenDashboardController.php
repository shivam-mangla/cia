<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;
use Models\Citizen;

class CitizenDashboardController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

    // TODO: get the user from the login cookies
    $username = $_COOKIE["username"];

		$citizen = Citizen::findByUsername($username);

		if(NULL === $citizen)
		{
			header('Location: /citizen/login?error_msg=login_required');
		}

    if($citizen->role == "employee")
    {
      echo $twig->render("employee_dashboard.html", array("username" => $username));
    }
    else if($citizen->role == "employer")
    {
      $report_ids = $citizen->getReportIds();

  		echo $twig->render("employer_dashboard.html", array("username" => $username, "report_ids" => $report_ids));
    }
  }

}