<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Создать топик</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="navbar.css">
</head>

<?php

	#
	#
	$cover_size_limit = 8; # MB

	#
	# Закрываем страницу для не авторизованных пользователей
	require "auth.class.php";

	if (isset($_POST['NAME']) && isset($_POST['POST'])) {

		$cover = '';

		if (isset($_FILES['COVER']['name']) && !empty($_FILES['COVER']['name']))
		{

			$upload_dir_cover = 'covers/';
			$cover = $upload_dir_cover . basename($_FILES['COVER']['name']);

			if ($_FILES['COVER']['size'] > 5000)
			{
				die("Максимальный размер обложки " . $cover_size_limit . "MB");
			}

			if (!move_uploaded_file($_FILES['COVER']['tmp_name'], $cover))
				die("Ошибка! Не получилось загрузить обложку, не является допустимым файлом или не может быть перемещен по какой-либо причине.");
		}

		$stdin->CreateTopic($_POST['NAME'], $_POST['POST'], $_SESSION['AUTH'], $cover) ? header('Location: /forum.php') : die('Error! Not Create New Topic :(');
	}
?>

<body>
	<main class="w-100 m-auto pb-5">


		<div class="m-1 m-md-5">
			<h1 class="text-center">Новый топик</h1>
			<form action="create_topic.php" method="post" enctype="multipart/form-data">
				<div class="mb-3">
					<label for="topicNameInput" class="form-label">Название</label>
					<input type="text" class="form-control" id="topicNameInput" name="NAME" placeholder="Топик про музыку">
				</div>
				<div class="mb-3">
					<label for="topicCoverInput" class="form-label">Обложка</label>
					<input type="file" class="form-control" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png">
				</div>
				<div class="mb-3">
					<label for="topicFirstPostInput" class="form-label">Тема топика</label>
					<textarea class="form-control" id="topicFirstPostInput" name="POST" placeholder="Мой любимый плейлист" rows="6"></textarea>
				</div>

				<div class="btn-group w-100" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-success bg-success bg-gradient btn-lg fw-bold">Создать</button>
				</div>
			</form>
		</div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center">
				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li class="text-center"><a href="forum.php" class="nav-link"><i class="bi bi-house mx-auto mb-1" style="font-size: 1.3rem;"></i><br>ГЛАВНАЯ</a></li>
					<li class="text-center"><a href="create_topic.php" class="nav-link"><i class="bi bi-bookmark-plus-fill mx-auto mb-1" style="font-size: 1.3rem;"></i><br>НОВЫЙ ТОПИК</a></li>
					<li class="text-center"><a href="#" class="nav-link"><i class="bi bi-gear mx-auto mb-1" style="font-size: 1.3rem;"></i><br>НАСТРОЙКИ</a></li>
				</ul>
			</div>
		</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>


</body>
</html>
