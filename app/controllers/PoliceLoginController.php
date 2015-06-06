<?php

use Models\PoliceMember;
use Models\PoliceStation;

class PoliceLoginController
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

	function post()
	{
		//TODO: apply validation checks and all here
		$username = $_POST["username"];
		$passwd = $_POST["passwd"];

		// TODO: check for login here
		// if valid redirect to /police/dashboard
		// else return to /police/login with error message
		header("Set-Cookie: username={$username}");
		header("Location: /police/dashboard");
	}

}