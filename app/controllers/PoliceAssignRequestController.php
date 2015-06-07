<?php

use Models\PoliceMember;
use Models\PoliceStation;

class PoliceAssignRequestController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

		$p_member = PoliceMember::findByUsername('kandoiabhi');
		$p_station = PoliceStation::find(1);

		// echo "Welcome to " . $p_station->name . ", Mr. " . $p_member->first_name;

    echo $twig->render("police_login.html", array());
	}

	function post($report_id)
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

		$usernames = $_POST["usernames"];
		$usernames = explode(",", $usernames);

		foreach ($usernames as $username) {
			$username = trim($username);

			PoliceMember::findByUsername($username)->assignReport($report_id);
		}

		echo $twig->render("police_report_assigned.html", array(
			"usernames" => $usernames,
			"report_id" => $report_id));
	}

}