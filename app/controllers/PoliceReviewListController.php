<?php

use Models\PoliceMember;
use Models\PoliceStation;
use Models\Report;

class PoliceReviewListController
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

    $report_ids = Report::findReportsAwaitingReviewForStation($p_member->getStationId());

    echo $twig->render("police_review_list_page.html", array(
    	"report_ids" => $report_ids
    	));
	}

}