<?php

use Models\Citizen;

class CitizenSignUpController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    	$twig = new \Twig_Environment($loader);
    	echo $twig->render("citizen_signup.html", array());
	}

	function post()
	{
		//TODO: do aadhaar authentication
	}

}