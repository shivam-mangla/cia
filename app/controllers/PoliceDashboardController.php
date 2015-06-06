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

		$open_report_count = Report::countOpenFor($p_member);
		// $inprocess_report_count = Report::countInprocessFor($p_member);

		$p_station = PoliceStation::find(1);
		$message = "Welcome to " . $p_station->name . ", Mr. " . $p_member->first_name;

		// TODO: check member type and render accordingly
    echo $twig->render("police_officer_dashboard.html", array(
    	"message" => $message,
    	"open_report_count" => $open_report_count
    	));
	}

}