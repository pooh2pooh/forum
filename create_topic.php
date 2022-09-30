<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Создать топик</title>
	<link rel="stylesheet" href="css/navbar.css">
</head>

<?php

	#
	#
	define('COVER_FILESIZE_MB', 8); # MB

	#
	# Закрываем страницу для не авторизованных пользователей
	require 'lib/auth.class.php';

	#
	# Системный класс
	require 'lib/system.class.php';

	if (isset($_POST['NAME']) && isset($_POST['POST'])) {

		$cover = '';

		if (isset($_FILES['COVER']['name']) && !empty($_FILES['COVER']['name']))
		{

			$filename = basename($_FILES['COVER']['name']);
			$cover = 'covers/' . $filename;
			$cover_thumb = 'covers/thumbs/' . $filename;

			if ($_FILES['COVER']['size'] > COVER_FILESIZE_MB*1000*1000)
				die('Максимальный размер обложки ' . COVER_FILESIZE_MB . 'MB');
			else if (!move_uploaded_file($_FILES['COVER']['tmp_name'], $cover))
				die('Ошибка! Не получилось загрузить обложку, не является допустимым файлом или не может быть перемещен по какой-либо причине.');

			#
			# Класс для конвертации изображений в WEBP
			require 'lib/webpConvert2.class.php';

			#
			# Класс для создания миниатюры изображения
			require 'lib/thumbs.class.php';
			
			$thumb = new Thumbs($cover);
			$thumb->cut(150, 150);

			if (webpConvert2($cover))
			{
				$thumb->saveWEBP($cover_thumb . '.webp', 80);
				$cover = $cover . '.webp';
			}
			else
				$thumb->save($cover_thumb);

		}

		$stdin->CreateTopic($_POST['NAME'], $_POST['POST'], $_SESSION['AUTH'], $filename) ? header('Location: /forum.php') : die('Ошибка! Не получилось создать новую тему :(');
	}
?>

<body>
	<main class="w-100 m-auto pb-5">


		<div class="m-1 m-md-5">
			<h1 class="text-center">Новая тема</h1>
			<form action="create_topic.php" method="post" enctype="multipart/form-data">
				<div class="mb-3">
					<label for="topicNameInput" class="form-label">Название</label>
					<input type="text" class="form-control" id="topicNameInput" name="NAME" placeholder="Топик про музыку" autofocus>
				</div>
				<div class="mb-5">
					<label for="topicCoverInput" class="form-label">Обложка</label>
					<input type="file" class="form-control" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png">
				</div>
				<div class="mb-3">
					<label for="topicFirstPostInput" class="form-label">Тема</label>
					<textarea class="form-control" id="topicFirstPostInput" name="POST" placeholder="Мой любимый плейлист" rows="6"></textarea>
				</div>

				<div class="btn-group w-100" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-success bg-success bg-gradient btn-lg fw-bold">Создать</button>
				</div>
			</form>
		</div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container-fluid">
			<div class="d-flex flex-wrap align-items-center justify-content-center">
				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li class="text-center small"><a href="forum.php" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
							  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ТЕМЫ</span>
					</a></li>
					<li class="text-center small"><a href="create_topic.php" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bookmark-plus-fill" viewBox="0 0 16 16">
						  <path fill-rule="evenodd" d="M2 15.5V2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.74.439L8 13.069l-5.26 2.87A.5.5 0 0 1 2 15.5zm6.5-11a.5.5 0 0 0-1 0V6H6a.5.5 0 0 0 0 1h1.5v1.5a.5.5 0 0 0 1 0V7H10a.5.5 0 0 0 0-1H8.5V4.5z"/>
						</svg>
						<br>
						<span style="font-size:0.7em">СОЗДАТЬ</span>
					</a></li>
					<li class="text-center small"><a href="#" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
						  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
						  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
						</svg>
						<br>
						<span style="font-size:0.7em">НАСТРОЙКИ</span>
					</a></li>
				</ul>
			</div>
		</div>
	</footer>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/bootstrap.bundle.min.js"></script>


</body>
</html>

<?php require "lib/print_runtime.php"; ?>
