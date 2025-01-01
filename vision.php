<?php 
header('Content-Type: application/json');
require 'vendor/autoload.php';

$sl = isset($_COOKIE['PHPSESSID'])
	? apcu_fetch('soft-limit:'.$_COOKIE['PHPSESSID'])
	: false;

if($sl) {
	$xc = apcu_fetch('xuyuan-chats:'.$_POST['page']);

	if(!$xc) {
		$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
		$dotenv->load();

		$data = array(
		    "content" => $_ENV['SYSTEM'],
		    "image_url" => "http://amertateknologi.com/xuyuan/images/term-9/1-9_".$_POST['page'].".jpg",
		    "id" => "1"
		);

		$ch = curl_init($_ENV['END_POINT_VISION']);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json'
		));

		$response = curl_exec($ch);

		if($response !== false) {
			apcu_store('xuyuan-chats:'.$_POST['page'], $response, 0);
		}

		curl_close($ch);
	} else {
		$response = $xc;
	}

	echo $response
		? $response
		: '{"error":"Maaf Aku sedang mengalami kendala!"}';
} else {
	echo '{"error":"Not Allowed"}';
}

