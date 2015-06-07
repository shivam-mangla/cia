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

		//TODO: Validate input data
		// Add input data to database
		$newuser = new Citizen;
		$newuser->aadhaar_no = $_POST['aadhaar_no'];
		$newuser->username = $_POST['username'];
		$newuser->passwd = $_POST['passwd'];
		$newuser->name = $_POST['name'];
		$newuser->dob = $_POST['dob'];
		$newuser->phone = $_POST['phone'];
		$newuser->email = $_POST['email'];


		// Aadhaar authentication
		$query = array(
			'aadhaar-id' => $_POST['aadhaar_no'],
			'modality' => 'demo',
			"certificate-type" => "preprod",
			'demographics' => array(
				'name' => array(
					"matching-strategy"=> "exact",
					'name-value' => $_POST['name']
				)
			),
			"location" => array(
				"type"=> "pincode",
				"pincode"=> "126102"
			 )
			);

		// Create Http context details
		$contextData = array (
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content'=> json_encode($query) );

		// Create context resource for our request
		$context = stream_context_create(array ( 'http' => $contextData ));

		// Read page rendered as result of your POST request
		$result =  file_get_contents (
		                  'https://ac.khoslalabs.com/hackgate/hackathon/auth/raw',  // page url
		                  false,
		                  $context);

		// var_dump($result);

		$result = json_decode($result);

		if($result->success) {
			$newuser->save();


		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
  	$twig = new \Twig_Environment($loader);

  	echo $twig->render("citizen_signup_success.html", array());
		} else {
			echo 'Not valid info for an aadhaar account!';
		}
	}

}