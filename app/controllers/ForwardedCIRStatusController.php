<?php

use Models\Citizen;
use Models\Report;
use Models\ForwardedCIR;

class ForwardedCIRStatusController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

    // TODO: get the user from the login cookies
    $username = $_COOKIE["username"];

		$citizen = Citizen::findByUsername($username);

		if(NULL === $citizen)
		{
			header('Location: /citizen/login?error_msg=login_required');
		}

		$cir_ids = Report::getReportIdsFor($citizen->aadhaar_no);

		$all_cir_status = array();

		foreach($cir_ids as $cir_id)
		{
			$multiple_status = ForwardedCIR::getMultipleStatusFor($cir_id);

			foreach($multiple_status as $status)
			{
				$receiver = Citizen::find($status["receiver_id"]);

				$status["receiver"] = $receiver->username;
				$all_cir_status[] = $status;
			}
		}

		var_dump($all_cir_status);

		// TODO: check status and show the relevant time stamp
    /*echo $twig->render("forwarded_cir_status.html", array(
    	"statuses" => $statuses_of_forwarded_reports
    	));*/
	}

}