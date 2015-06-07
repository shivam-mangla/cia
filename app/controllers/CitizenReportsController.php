<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;
use Models\Citizen;

class CitizenReportsController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

    // TODO: get the user from the login cookies
    $username = $_COOKIE["username"];

		$p_member = Citizen::findByUsername($username);

		if(NULL === $p_member)
		{
			header('Location: /citizen/login?error_msg=login_required');
		}

    $report_ids = $p_member->getReportIds();

    echo $twig->render("citizen_list_received_reports.html", array(
    	"report_ids" => $report_ids
    	));
	}

}