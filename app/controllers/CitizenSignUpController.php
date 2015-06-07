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

		//TODO: Write that data into db


		// Aadhaar authentication
		$query = array(
			'demographics' => array(
				'name' => array(
					'name-value' => $_POST('name')
				),
				'dob' => array(
					'dob-value' => $_POST('dob')
				),
				'email' => $_POST('email'),
				'phone' => $_POST('phone')
			)
			);


		// Create Http context details		
		$contextData = array ( 
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content'=> json_encode($query) );

		// Create context resource for our request
		$context = stream_context_create (array ( 'http' => $contextData ));

		// Read page rendered as result of your POST request
		$result =  file_get_contents (
		                  'https://ac.khoslalabs.com/hackgate/hackathon/auth/raw',  // page url
		                  false,
		                  $context);

	}

}