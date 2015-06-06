<?php

use Models\PoliceMember;

class PoliceLoginController
{

	function get()
	{
		$p_member = PoliceMember::findByUsername('kandoiabhi');

		echo "Welcome to Police Station " . $p_member->first_name;
	}

}