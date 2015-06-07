<?php

class HomeController
{

	function get()
	{
		$loader = new \Twig_Loader_Filesystem(__DIR__ . '/../views');
    	$twig = new \Twig_Environment($loader);

    echo $twig->render("homepage.html", array());
	}

	function post()
	{
		echo "<br><br>You don't always POST thing. Sometimes you GET them!";
	}

}