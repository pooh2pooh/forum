<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">

	<main class="form-signin w-100 m-auto">

<?php

require "stdout.class.php";
$stdout = new stdout();

!isset($_SESSION['AUTH']) ?: header('Location: /forum.php');
if (isset($_POST['USERNAME']) && isset($_POST['PASSWORD'])) {

				($stdout->Auth($_POST['USERNAME'], $_POST['PASSWORD']) ? header('Location: /forum.php') : die('<h1>Упс! Тебя тут не ждали,</h1>лучше больше не пытайся.')) ?: $_SESSION['AUTH'] = $_POST['USERNAME'];

}

?>

		<form action="/" method="post">
			<h1 class="h3 mb-3 fw-normal">осталась 1 попытка</h1>

			<div class="form-floating">
				<input type="text" class="form-control" id="inputLogin" name="USERNAME" placeholder="username">
				<label for="inputLogin">дело</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="inputPassword" name="PASSWORD" placeholder="password">
				<label for="inputPassword">времени</label>
			</div>

			<div class="checkbox mb-3">
				<label>
					<input type="checkbox" value="remember-me"> запомнить (7 дн.)
				</label>
			</div>
			<button class="w-100 btn btn-lg btn-primary bg-gradient" type="submit"><i class="bi bi-fingerprint h1"></i> приложи к экрану</button>
			<p class="mt-5 mb-3 text-muted">Разбан платный, 1 млн. руб.<br>&copy; <strong>2022</strong>, скоро 2023</p>
		</form>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
