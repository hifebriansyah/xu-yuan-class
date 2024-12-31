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
		:root {
			/* green */
			--primary: rgb(1,104,170);
			--secondary: #F7C204;
			--accent: #00bfa5;
		}
		.logo {
			display: block;
			margin: 0 auto;
			width: 300px;
		}
		.row img{
			border: 1px solid #ddd;
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
	</style>
</head>
<body>
	<img loading="lazy" class="logo" src="assets/logo.png">
	<div class="container">
		<div class="row">
			<?php 
				$len = $_GET['page'] ?? 47;
				$i = $_GET['page'] ?? 1;
				for ($i; $i <= $len ; $i++) { 
			?>
				<div class="col-sm-6 col-md-3 pb-4">
					<a href="detail.php?page=<?= $i ?>">
						<img loading="lazy" src="http://amertateknologi.com/xuyuan/images/term-9-hd/1-9_<?= $i ?>.jpg">
						<div>#<?= $i ?></div>
					</a>
				</div>
			<?php
				}
			?>
		</div>
	</div>
</body>
</html>