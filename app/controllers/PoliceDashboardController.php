<?php

use Models\PoliceMember;
use Models\PoliceStation;

class PoliceDashboardController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    $twig = new \Twig_Environment($loader);

    // TODO: get the user from the login cookies
    $username = $_COOKIE["username"];

		$p_member = PoliceMember::findByUsername($username);

		$p_station = PoliceStation::find(1);
		$message = "Welcome to " . $p_station->name . ", Mr. " . $p_member->first_name;

		// TODO: check member type and render accordingly
    echo $twig->render("police_officer_dashboard.html", array(
    	"message" => $message
    	));
	}

}