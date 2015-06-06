<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceOpenReportsController
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

		$open_report_count = Report::countWithStatusFor("open", $p_member);
		$open_reports = Report::findWithStatusFor("open", $p_member->id);

		// TODO: check member type and render accordingly
    echo $twig->render("police_dashboard_open_reports.html", array(
    	"open_report_count" => $open_report_count,
    	"open_reports" => $open_reports
    	));
	}

}