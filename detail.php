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

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
	$dotenv->load();

	$sl = apcu_fetch('soft-limit:'.session_id());

	if(!$sl) {
		$sl = 100;
		apcu_store('soft-limit:'.session_id(), 100, 0);
	}
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
	</style>
</head>
<body id="detail-page">
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
						<div class="chats d-none">
							<div>
								<div class="avatar"></div>
							</div>
							<div class="content">
								<div class="lina"><i class="bi bi-slash-circle spin"></i></div>
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

				let rand = "a"+Date.now()
				chatElement = document.querySelector(".chats.d-none").cloneNode(true);
				chatElement.classList.remove("d-none")
				chatElement.classList.add(rand)
				document.querySelector(".chat-body").innerHTML += chatElement.outerHTML;

				document.querySelector(".chat-body").scrollTop = document.querySelector(".chat-body").scrollHeight;

				const formData = new FormData();
				formData.append('text', text);
				formData.append('page', <?= $_GET['page'] ?>);

				fetch('./chat.php', {
					method: 'POST',
					credentials: 'include',
					body: formData
				})
				.then(response => response.json())
				.then(data => {
					document.querySelector("."+rand+" .lina").innerHTML = data.error || parseMarkdown(data.choices[0].message.content);
					document.querySelector(".chat-body").scrollTop = document.querySelector(".chat-body").scrollHeight;

					el.setAttribute('onclick', 'chat(this)');
				})
				.catch(console.log);
			}
		}

		function vision() {

			let rand = "a"+Date.now()
			let chatElement = document.querySelector(".chats.d-none").cloneNode(true);
			chatElement.classList.remove("d-none")
			chatElement.classList.add(rand)
			document.querySelector(".chat-body").innerHTML += chatElement.outerHTML;

			const formData = new FormData();
			formData.append('page', <?= $_GET['page'] ?>);

			fetch('./vision.php', {
				method: 'POST',
				credentials: 'include',
				body: formData
			})
			.then(response => response.json())
			.then(data => {
				document.querySelector("."+rand+" .lina").innerHTML = data.error || parseMarkdown(data.choices[0].message.content);
			})
			.catch(console.log);
		}

		function parseMarkdown(md) {
		    return md
		        .replace(/^# (.*$)/gm, '<h1>$1</h1>')
		        .replace(/^## (.*$)/gm, '<h2>$1</h2>')
		        .replace(/\*\*(.*?)\*\*/gm, '<strong>$1</strong>')
		        .replace(/\*(.*?)\*/gm, '<em>$1</em>')
		        .replace(/^- (.*$)/gm, '<li>$1</li>')
		        .replace(/(<li>.*<\/li>)/gm, '<ul>$1</ul>')
		        .replace(/\n/g, '<div class="mb-3"></div>');
		}

		vision();
	</script>
</body>
</html>