<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceSingleRequestController
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

    $report = Report::find($report_id);

		$aadhaar_no = $report->aadhaar_no;

    echo $twig->render("police_report_page.html", array(
    	"report_id" => $report_id,
    	"aadhaar_no" => $aadhaar_no
    	));
	}

}