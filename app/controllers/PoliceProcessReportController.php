<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceProcessReportController
{

	function get($report_id)
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

		// TODO: check if the user has access to this report
		// assuming yes for now

		$report = Report::find($report_id);

		if($report->status == "open")
		{
			$report->status = "in_process";
			$report->save();
		}

		// TODO: Fetch all the records for the report
		$records = array();

    echo $twig->render("police_process_report.html", array(
    	"report_id" => $report_id,
    	"records" => $records
    	));
	}

}