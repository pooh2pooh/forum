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

				($stdout->Auth($_POST['USERNAME'], $_POST['PASSWORD']) ? header('Location: /forum.php') : die("<div class='text-center py-5'><img class='img-fluid' src='system-page-cover.png'></div><!-- Что ты здесь хотел увидеть ? -->")) ?: $_SESSION['AUTH'] = $_POST['USERNAME'];

}

?>

		<form action="/" method="post">
			<h1 class="mb-3 fw-bold small text-danger">осталась 1 попытка</h1>

			<div class="form-floating">
				<input type="text" class="form-control" id="inputLogin" name="USERNAME" placeholder="username">
				<label for="inputLogin">позывной</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="inputPassword" name="PASSWORD" placeholder="password">
				<label for="inputPassword">пароль</label>
			</div>

			<button class="w-100 btn btn-lg btn-primary bg-gradient mt-3" type="submit">Вход</button>
			<p class="mt-5 mb-3 text-muted">С <i class="bi bi-heart-fill text-danger"></i> из России</p>
		</form>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
