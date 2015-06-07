<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceDashboardController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

    // TODO: get the user from the login cookies
    $username = $_COOKIE["username"];

		$p_member = PoliceMember::findByUsername($username);

		if(NULL === $p_member)
		{
			header('Location: /police/login?error_msg=login_required');
		}

    $p_station = PoliceStation::find(1);
    $message = "Welcome to " . $p_station->name . ", Mr. " . $p_member->first_name;

    // TODO: check member type and render accordingly

    if($p_member->role == "p_commissioner")
    {
      $open_report_count = $p_station->newRequestsCount();
      $review_report_count = $p_station->reviewRequestsCount();

      echo $twig->render("police_commissioner_dashboard.html", array(
        "message" => $message,
        "open_report_count" => $open_report_count,
        "review_report_count" => $review_report_count
        ));
    }
    else if($p_member->role == "p_officer")
    {
  		$open_report_count = Report::countWithStatusFor("open", $p_member);
  		$inprocess_report_count = Report::countWithStatusFor("in_process", $p_member);
      $inreview_report_count = Report::countWithStatusFor("in_review", $p_member);

      echo $twig->render("police_officer_dashboard.html", array(
        "message" => $message,
        "open_report_count" => $open_report_count,
        "inprocess_report_count" => $inprocess_report_count,
        "inreview_report_count" => $inreview_report_count
        ));
    }
  }

}