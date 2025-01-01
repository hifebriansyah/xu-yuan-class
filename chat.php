<?php 
header('Content-Type: application/json');
require 'vendor/autoload.php';

$sl = apcu_fetch('soft-limit');

if(isset($_COOKIE['PHPSESSID']) && isset($sl[$_COOKIE['PHPSESSID']])) {
	$data = array(
	    "content" => $_POST['text'],
	    "id" => $_COOKIE['PHPSESSID']
	);

	$sl = apcu_fetch('soft-limit');

	if($sl[$_COOKIE['PHPSESSID']] > 0){
		$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
		$dotenv->load();

		$ch = curl_init($_ENV['END_POINT_CHAT']); // Replace with your API endpoint URL

		// Set curl options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json'
		));

		$response = curl_exec($ch);

		curl_close($ch);
		$sl[$_COOKIE['PHPSESSID']] -= 1;
		apcu_store('soft-limit', $sl);

		echo $response;
	} else {
		echo '{"message":"Maaf, koin kamu sudah habis.."}';	
	}

} else {
	echo '{"message":"Not Allowed"}';
}
