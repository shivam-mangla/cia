<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceRequestsController
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

    $p_station = PoliceStation::find($p_member->getStationId());

		$reports_requests_count = $p_station->newRequestsCount();
		$reports_requests = Report::findRequestsForStation($p_member->getStationId());

    echo $twig->render("police_dashboard_reports_requests.html", array(
    	"reports_requests_count" => $reports_requests_count,
    	"reports_requests" => $reports_requests
    	));
	}

}