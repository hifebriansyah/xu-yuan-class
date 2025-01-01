<?php 
header('Content-Type: application/json');
require 'vendor/autoload.php';

$sl = isset($_COOKIE['PHPSESSID'])
	? apcu_fetch('soft-limit:'.$_COOKIE['PHPSESSID'])
	: false;

if($sl) {
	$data = array(
	    "content" => $_POST['text'],
	    "id" => $_COOKIE['PHPSESSID']
	);

	if($sl > 0){
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
		$sl -= 1;
		apcu_store('soft-limit:'.$_COOKIE['PHPSESSID'], $sl, 0);

		echo $response;
	} else {
		echo '{"message":"Maaf, koin kamu sudah habis.."}';	
	}

} else {
	echo '{"message":"Not Allowed"}';
}
