<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceInprocessReportsController
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

		$inprocess_report_count = Report::countWithStatusFor("in_process", $p_member);
		$inprocess_reports = Report::findWithStatusFor("in_process", $p_member->id);

		// TODO: check member type and render accordingly
    echo $twig->render("police_dashboard_inprocess_reports.html", array(
    	"inprocess_report_count" => $inprocess_report_count,
    	"inprocess_reports" => $inprocess_reports
    	));
	}

}