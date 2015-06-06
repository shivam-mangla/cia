<?php

use Models\PoliceMember;
use Models\PoliceStation;

class PoliceLoginController
{

	function get()
	{
		$p_member = PoliceMember::findByUsername('kandoiabhi');
		$p_station = PoliceStation::find(1);

		echo "Welcome to " . $p_station->name . ", Mr. " . $p_member->first_name;
	}

}