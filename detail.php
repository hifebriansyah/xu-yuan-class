<?php
	if($_GET['page'] < 1) {
		header("Location: ?page=47");
		exit;
	}
	if($_GET['page'] > 47) {
		header("Location: ?page=1");
		exit;
	}

	require 'vendor/autoload.php';

	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}

	$parsedown = new Parsedown();
	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$sl = apcu_fetch('soft-limit:'.session_id());

	if(!$sl) {
		$sl = 100;
		apcu_store('soft-limit:'.session_id(), 100, 0);
	}

	$xc = apcu_fetch('xuyuan-chats:'.$_GET['page']);

	if(!$xc) {
		$data = array(
		    "content" => $_ENV['SYSTEM'],
		    "image_url" => "http://amertateknologi.com/xuyuan/images/term-9/1-9_".$_GET['page'].".jpg",
		    "id" => "1"
		);

		$ch = curl_init($_ENV['END_POINT_VISION']);

		// Set curl options
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Content-Type: application/json'
		));

		$response = curl_exec($ch);

		if($response !== false) {
		    $response = json_decode($response);
			apcu_store('xuyuan-chats:'.$_GET['page'], $response, 0);
		}

		curl_close($ch);
	} else {
		$response = $xc;
	}
	
	$lorem = "Maaf Aku sedang mengalami kendala!";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Xu Yuan Class</title>
	<link rel="stylesheet" href="assets/bootstrap/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/style.css?v=1.1" />
	<link rel="stylesheet" href="assets/icon/bootstrap-icons.min.css">
	<link rel="manifest" href="assets/fav/site.webmanifest">

	<link rel="apple-touch-icon" sizes="180x180" href="assets/fav/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="assets/fav/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="assets/fav/favicon-16x16.png">

	<style type="text/css">
		body {

		}
		:root {
			/* green */
			--primary: rgb(1,104,170);
			--secondary: #F7C204;
			--accent: #00bfa5;
		}
		.logo {
			display: block;
			margin: 32px auto;
			width: 100px;
			margin-bottom: 16px;
		}
		.container img{
			border: 1px solid var(--secondary);
			padding: 5px;
			max-width: 100%;
			border-radius: 30px;
		}
		.row a {
			text-align: center;
		}
		a {
			color: var(--accent);
			text-decoration: none !important;
		}
		.chat-container {
			border-radius: 8px;
			overflow: hidden;
			position: relative;
		}
		.chat-container::before {
			background-color: #d0d0d0;
			content:' ';
			display: inline-block;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			position: absolute;
			opacity: 0.1;
			background-image: url("assets/download.svg");
			z-index: -1;
		}
		.chat-header {
			padding: 16px;
			background: var(--primary);
			color: #fff;
			margin-right: 8px;
			width: 100%;
		}
		.chat-body {
			height: 400px;
			overflow-y: scroll;
			overflow-x: hidden;
			padding: 8px 0;
			border: 1px solid #ddd;
		}
		.chat-footer {
			background: #F4F7F9;
			display: inline-flex;
			width: 100%;
			border: 1px solid #ddd;
			border-top: none ;
			border-radius: 0px 0px 8px 8px;
		}
		.input-chat {
			background: none;
			border: none;
			padding: 8px 16px;
			flex-grow: 1;
			height: 62px;
            resize: none;
			margin: 0;
			display: block;
			float: left;
			margin-right: 8px;
			padding-top: 18px;
		}
		.input-chat:* {
			border: none !important;
			box-shadow: none !important;
		}
		.chats {
			padding: 8px 16px;
			position: relative;
			clear: both;
		}
		.chats:after {
			display: block;
			content:' ';
			clear: both;
		}
		.chats .avatar {
			width: 38px;
			height: 38px;
			background: red;
			border-radius: 50px;
			overflow: hidden;
			position: absolute;
			top: 18px;
			background: url("assets/lina.jpg") no-repeat;
			background-size: cover;
			left: 8px;
		}
		.chats .content {
			background: white;
			padding: 16px;
			border-radius: 16px;
			border: 1px solid #f0f0f0;
			float: left;
			margin-left: 36px;
			margin-right: 36px;
		}

		.chats.me .content {
			float: right;
		}

		.chats.me .avatar {
			margin-left: 16px;
			margin-right: 0px;
			left: unset;
			right: 8px;
			background: url("assets/user.png") no-repeat;
			background-size: cover;
		}

		.chat-footer .bi::before {
			font-size: 30px;
			padding: 16px;
			padding-right: 24px;
			color: var(--accent);
			cursor: pointer;
		}

		input:focus,
		textarea:focus,
		button:focus,
		select:focus {
			outline: none;
			box-shadow: none;
		}

		.nav-slide a {
			padding: 12px 0px;
		}

		.pointer {
			cursor: pointer;
		}
	</style>
</head>
<body>
	<img loading="lazy" class="logo" src="assets/logo.png">
	<div class="container">
		<a href="./">Menu</a> <i class="bi bi-chevron-compact-right" style="font-size: 12px;"></i> #<?= $_GET['page'] ?></a>
		<div class="row mt-3">
			<div class="col-lg-7 mb-3">
				<img loading="lazy" src="http://amertateknologi.com/xuyuan/images/term-9-hd/1-9_<?= $_GET['page'] ?>.jpg">
				<div class="d-block nav-slide">
					<a class="float-start" href="?page=<?= $_GET['page'] - 1 ?>"><i class="bi bi-arrow-left"></i> Prev</a>
					<a class="float-end" href="?page=<?= $_GET['page'] + 1 ?>">Next <i class="bi bi-arrow-right"></i></a>
				</div>
			</div>
			<div class="col-lg-5 mb-3">
				<div class="chat-container">
					<div class="chat-header">Lina Laoshi ( AI ) <span class="float-end">Sisa <span id="koin"><?= $sl ?></span> Koin</span></div>
					<div class="chat-body">
						<div class="chats me">
							<div>
								<div class="avatar"></div>
							</div>
							<div class="content">Hi Lina Laoshi, tolong ajarkan aku materi di gambar tersebut..</div>
						</div>
						<div class="chats">
							<div>
								<div class="avatar"></div>
							</div>
							<div class="content">
								<div class="lina"><?= isset($response) ? $parsedown->text("Hai, ".$response->choices[0]->message->content) : $lorem ?></div>
								<div onclick="speech(this)" class="float-end pointer"><i class="bi bi-soundwave"></i></div>
							</div>
						</div>
					</div>
					<div class="chat-footer">
						<textarea class="input-chat" id="input-chat" placeholder="Yuk tanya laoshi!"></textarea>
						<i class="bi bi-send-fill" onclick="chat(this)"></i>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		function speech(el) {
			const speech = new SpeechSynthesisUtterance(el.previousElementSibling.textContent);
			speech.lang = 'id-ID';
			speechSynthesis.cancel();
			window.speechSynthesis.speak(speech);
		}

		function chat(el) {

			const text = document.getElementById('input-chat').value;

			if(text) {
				document.getElementById('koin').textContent--
				el.removeAttribute('onclick');

				document.getElementById('input-chat').value = '';

				let chatElement = document.querySelector(".chats.me").cloneNode(true);
				chatElement.querySelector(".content").innerHTML = text;
				document.querySelector(".chat-body").innerHTML += chatElement.outerHTML;
				document.querySelector(".chat-body").scrollTop = document.querySelector(".chat-body").scrollHeight;

				const formData = new FormData();
				formData.append('text', text);

				fetch('./chat.php', {
					method: 'POST',
					credentials: 'include',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					let chatElement = document.querySelector(".chats:not(.me)").cloneNode(true);
					chatElement.querySelector(".lina").innerHTML = data.message || data.choices[0].message.content;
					document.querySelector(".chat-body").innerHTML += chatElement.outerHTML;
					document.querySelector(".chat-body").scrollTop = document.querySelector(".chat-body").scrollHeight;
					el.setAttribute('onclick', 'chat(this)');
				})
				.catch(console.log);
			}
		}
	</script>
</body>
</html>