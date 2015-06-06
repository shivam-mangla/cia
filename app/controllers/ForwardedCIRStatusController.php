<?php

use Models\Citizens;
use Models\CIR;
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


		$cir_id = CIR::getCIRId($citizen->id);
		$statuses_of_forwarded_reports = ForwardedCIR::getStatuses($cir->id);

		for $status in $statuses_of_forwarded_reports {
			$statuses_of_forwarded_reports('employer_name') = $findByCitizenId($status(0));
		}

		var_dump($statuses_of_forwarded_reports);

		// TODO: check status and show the relevant time stamp
    echo $twig->render("forwarded_cir_status.html", array(
    	"statuses" => $statuses_of_forwarded_reports
    	));
	}

}