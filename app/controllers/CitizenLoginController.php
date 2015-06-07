<?php

use Models\Citizen;

class CitizenLoginController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    	$twig = new \Twig_Environment($loader);

		$citizen = Citizen::findByUsername('mangla');

		echo "Welcome Mr. " . $citizen->username;

    	// echo $twig->render("police_login.html", array());
	}

	function post()
	{
		//TODO: apply validation checks and all here
		$username = $_POST["username"];
		$passwd = $_POST["passwd"];

		// TODO: check for login here
		// if valid redirect to /citizen/dashboard
		// else return to /citizen/login with error message
		header("Set-Cookie: username={$username}");
		header("Location: /citizen/dashboard");
	}

}