<?php

namespace App\Services;

use DB;


class PhoneService
{

	public static function data($token)
	{
		// Historique
		$url = "https://api.telavox.se/calls";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($ch);
		curl_close($ch);
		$callData = json_decode($response, true);

		return $callData;

	}
}
