<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link href="signin.css" rel="stylesheet">
</head>

<body class="text-center">

	<main class="form-signin w-100 m-auto">

<?php

require "stdout.class.php";
$stdout = new stdout();

!isset($_SESSION['AUTH']) ?: header('Location: /forum.php');
if (isset($_POST['USERNAME']) && isset($_POST['PASSWORD'])) {

				($stdout->Auth($_POST['USERNAME'], $_POST['PASSWORD']) ? header('Location: /forum.php') : die('Error!')) ?: $_SESSION['AUTH'] = $_POST['USERNAME'];

}

?>

		<form action="/" method="post">
			<h1 class="h3 mb-3 fw-normal">Please sign in</h1>

			<div class="form-floating">
				<input type="text" class="form-control" id="inputLogin" name="USERNAME" placeholder="username">
				<label for="inputLogin">Login</label>
			</div>
			<div class="form-floating">
				<input type="password" class="form-control" id="inputPassword" name="PASSWORD" placeholder="password">
				<label for="inputPassword">Password</label>
			</div>

			<div class="checkbox mb-3">
				<label>
					<input type="checkbox" value="remember-me"> Remember me
				</label>
			</div>
			<button class="w-100 btn btn-lg btn-primary bg-gradient" type="submit">Sign in</button>
			<p class="mt-5 mb-3 text-muted">&copy; 2022</p>
		</form>
	</main>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
