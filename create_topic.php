<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Закрытый форум Харибда, дважды подумай, прежде чем сказать.">
	<title>Создать топик</title>
	<link rel="stylesheet" href="css/navbar.css">
</head>

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require 'lib/auth.class.php';

	#
	# Системный класс
	require 'lib/system.class.php';

	if (!empty($_POST['NAME']) && !empty($_POST['POST']) && !empty($_POST['CATEGORY']))
	{

		$stdin->CreateTopic($_POST['NAME'], $_POST['POST'], $_POST['CATEGORY'], $_SESSION['AUTH'], uploadCover($_FILES['COVER'])) ? header('Location: /forum.php') : die('Ошибка! Не получилось создать новую тему :(');

	}
?>

<body>
	<main class="w-100" style="min-height: 1100px;">

		<div class="container">
			<form action="create_topic.php" method="post" enctype="multipart/form-data">
				<div class="row my-5">
					<div class="col-md-8 mx-auto">
						<label class="h4 mb-3 fw-bold" for="topicNameInput">Название темы:</label>
						<input class="form-control form-control-lg border-0 mb-4" id="topicNameInput" name="NAME" placeholder="Должно точно отражать суть" autofocus>

						<label class="h4 mb-3 fw-bold" for="topicFirstPostInput">Краткое описание:</label>
						<textarea class="form-control form-control-lg border-0 mb-4" id="topicFirstPostInput" name="POST" placeholder="Можно использовать стандартные html теги для оформления" rows=12></textarea>

						<label class="h5 mb-3" for="topicCategorySelect">Категория</label>
						<select class="form-select mb-4" aria-label="Select category topic" id="topicCategorySelect" name="CATEGORY">
							<?php
								$categories = $stdout->ListCategories();
								foreach($categories as $category)
								{
									echo "<option value=$category[id]>$category[name]</option>";
								}
							?>
						  
						</select>
						
						<label class="h5 mb-3" for="topicCoverInput">Обложка (необязательно)</label>
						<input type="file" class="form-control border-0 mb-5" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png,.webp">

						<button type="submit" class="btn btn-success bg-success bg-gradient btn-lg fw-bold w-100">Создать</button>
						
					</div>
				</div>
			</form>
		</div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container-fluid">
			<div class="d-flex">
				<ul class="nav col-12 my-1 my-md-0 text-small justify-content-evenly">
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
					<li class="text-center small"><a href="settings.php" class="nav-link">
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
	<script src="js/bootstrap.bundle.min.js" defer></script>


</body>
</html>

<?php require "lib/print_runtime.php"; ?>
