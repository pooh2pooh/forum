<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Настройки аккаунта, — закрытый форум Харибда">
	<title>Настройки аккаунта</title>
	<link rel="stylesheet" href="css/navbar.css">
</head>

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require 'lib/auth.class.php';

	#
	# Системный класс
	require 'lib/system.class.php';

?>

<!-- ВЁРСТКА СТРАНИЦЫ -->
<body>
	<main class="w-100 bg-light m-auto" style="min-height: 1600px;">

		<!-- НАСТРОЙКИ -->
		<div class="container">
			<div class="row">

				

			</div>
		</div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container-fluid">
			<div class="d-flex">
				<ul class="nav col-12 my-1 my-md-0 text-small justify-content-evenly">
					<li class="text-center small">
						<a href="forum.php" class="nav-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bookmarks" viewBox="0 0 16 16">
							  <path d="M2 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v11.5a.5.5 0 0 1-.777.416L7 13.101l-4.223 2.815A.5.5 0 0 1 2 15.5V4zm2-1a1 1 0 0 0-1 1v10.566l3.723-2.482a.5.5 0 0 1 .554 0L11 14.566V4a1 1 0 0 0-1-1H4z"/>
							  <path d="M4.268 1H12a1 1 0 0 1 1 1v11.768l.223.148A.5.5 0 0 0 14 13.5V2a2 2 0 0 0-2-2H6a2 2 0 0 0-1.732 1z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ФОРУМ</span>
						</a>
					</li>
					<li class="text-center small">
						<a class="nav-link" href="#save">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-person-badge" viewBox="0 0 16 16">
							  <path d="M6.5 2a.5.5 0 0 0 0 1h3a.5.5 0 0 0 0-1h-3zM11 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
							  <path d="M4.5 0A2.5 2.5 0 0 0 2 2.5V14a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2.5A2.5 2.5 0 0 0 11.5 0h-7zM3 2.5A1.5 1.5 0 0 1 4.5 1h7A1.5 1.5 0 0 1 13 2.5v10.795a4.2 4.2 0 0 0-.776-.492C11.392 12.387 10.063 12 8 12s-3.392.387-4.224.803a4.2 4.2 0 0 0-.776.492V2.5z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ПРОФИЛЬ</span>
						</a>
					</li>
					<li class="text-center small">
						<a href="#save" class="nav-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
							  <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z"/>
							</svg>
						<br>
						<span style="font-size:0.7em">СОХРАНИТЬ</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</footer>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.6.1.min.js" defer></script>
	<script src="js/bootstrap.bundle.min.js" defer></script>


</body>
</html>

<?php require 'lib/print_runtime.php'; ?>