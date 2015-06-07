<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceReportVerificationController
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

		if($report->status == "in_review")
		{
      $report->markVerifiedBy($p_member->id);
			$report->status = "closed";
			$report->save();
		}

    echo $twig->render("police_report_marked_as_verified.html", array(
    	"report_id" => $report_id
    	));
	}

}