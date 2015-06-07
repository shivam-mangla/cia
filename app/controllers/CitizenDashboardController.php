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

    // TODO: check member type and render accordingly

    echo "Welcome to your dash {$username}<br>";

    if($citizen->role == "employee")
    {
      echo "<a href=\"/citizen/status_fcir\">Status of forwarded reports</a>";

      /*echo $twig->render("police_commissioner_dashboard.html", array(
        "message" => $message,
        "open_report_count" => $open_report_count,
        "review_report_count" => $review_report_count
        ));*/
    }
    else if($citizen->role == "employer")
    {
  		echo "You are an employer!";

      /*echo $twig->render("police_officer_dashboard.html", array(
        "message" => $message,
        "open_report_count" => $open_report_count,
        "inprocess_report_count" => $inprocess_report_count,
        "inreview_report_count" => $inreview_report_count
        ));*/
    }
  }

}