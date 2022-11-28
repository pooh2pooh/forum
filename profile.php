<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require 'lib/auth.class.php';

	#
	# Системный класс
	require 'lib/system.class.php';

	#
	# Закрываем страницу, если не указан пользователь
	empty($_GET['user']) ? $user = '' : $user = htmlspecialchars($_GET['user']);
	if (!strlen($user)) {
		die('<title>Профиль не найден</title><link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такого пользователя</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');
	} else {
		$profile = $stdout->GetProfile($user, $_SESSION['USER']['username']);
		if (!$profile)
			die('<title>Профиль не найден</title><link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такого пользователя</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');
	}

?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Закрытый форум Харибда, профиль <?=$profile['username']?>">
	<title>Профиль <?=$profile['username']?></title>
	<link rel="stylesheet" href="css/navbar.css">
</head>


<!-- ВЁРСТКА ТОПИКА -->
<body class="bg-light">
	<main class="w-100 m-auto">
		<div class="container-fluid" style="position:absolute;">

			<aside class="sticky-top d-none d-lg-block m-5 float-end" style="height: 100%; max-width: 25%;">
					<div class="list-group shadow-sm">
						<a class="list-group-item list-group-item-action px-5 bg-danger bg-gradient text-light" href="#" onclick="history.go(-1); return false;">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x" viewBox="0 0 16 16">
							  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
							</svg>
							Закрыть
						</a>
						<?php if (!strcmp($_SESSION['USER']['login'], $_GET['user'])) { ?>
							<a class="list-group-item list-group-item-action px-5 bg-light" href="settings.php">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen" viewBox="0 0 16 16">
								  <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001zm-.644.766a.5.5 0 0 0-.707 0L1.95 11.756l-.764 3.057 3.057-.764L14.44 3.854a.5.5 0 0 0 0-.708l-1.585-1.585z"/>
								</svg>
								Изменить
							</a>
						<?php } ?>
					</div>
					<div class="list-group shadow-sm pt-2">
						<a class="list-group-item list-group-item-action px-5 bg-light" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
							  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
							</svg>
							Вверх
						</a>
					</div>

					<?php require 'lib/print_myprofile.php'; ?>
					<?php require 'lib/print_activity.php'; ?>
				</aside>

				<div class="row">

<?php

	echo '<h1 class="pt-5 fw-bold">' . $profile['username'] . '</h1>';

	if ($profile['lastfm_account']) {

		$lastfm_api_key = $stdout->GetConfig('lastfm_api_key');
		$lastfm_account = urlencode($profile['lastfm_account']);
		$lastfm_api_query = curl_init("https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=$lastfm_account&api_key=$lastfm_api_key&format=json&limit=5");
		curl_setopt($lastfm_api_query, CURLOPT_RETURNTRANSFER, true);

		if ( ($lastfm = curl_exec($lastfm_api_query) ) === false)
			echo 'Curl error: ' . curl_error($lastfm_api_query);
		else $lastfm = json_decode($lastfm, true);

		curl_close($lastfm_api_query);

		if (!strcmp($_SESSION['USER']['username'], $_GET['user']) && isset($lastfm['error']) && $lastfm['error'] == 6)
			echo '<h3 class="text-danger"><span class="fw-bold">lastfm:</span> не найдет логин ' . $lastfm_account . '</h3>';

		if (isset($lastfm['recenttracks']['track']) && count($lastfm['recenttracks']['track'])) {
			
			echo '<h3>Недавно слушал:</h3>';
			echo '<ul class="list-group list-group-flush">';
			foreach ($lastfm['recenttracks']['track'] as $track) {
				if(!isset($track['date']['#text']))
					$timestamp = '<span class="small text-success">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
													  <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
													</svg>
													сейчас проигрывается
												</span>';
				else
					$timestamp = '<span class="text-muted text-nowrap" style="font-size: 0.7em">' . $track['date']['#text']  . '</span>';
				echo '<a class="list-group-item list-group-item-action fw-bold text-dark d-flex py-3 py-sm-2" href="' . $track['url'] . '" target="_blank">';
				echo '<span class="flex-fill text-break"><img class="px-2" src="' . $track['image'][0]['#text'] . '">'; // 0 - small, 1 - medium, 2 - large size for track cover
				echo $track['artist']['#text'] . ' — ' . $track['name'] . '</span>' . $timestamp . '</a>';
			}
			echo '</ul>';
		}

	}

?>
				</div>

		</div>
	</main>


	<footer class="fixed-bottom bg-navbar d-lg-none">
		<div class="container-fluid">
			<div class="d-flex">
				<ul class="nav col-12 my-1 my-md-0 text-small justify-content-evenly">
					<li class="text-center small">
						<a href="forum.php" class="nav-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
							  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ФОРУМ</span>
						</a>
					</li>
					<li class="text-center small">
						<a class="nav-link" data-bs-toggle="modal" href="#" role="button"  onclick="history.go(-1); return false;">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="pink" class="bi bi-x" viewBox="0 0 16 16">
							  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ЗАКРЫТЬ</span>
						</a>
					</li>
					<li class="text-center small">
						<a href="settings.php" class="nav-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
							  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
							  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
							</svg>
						<br>
						<span style="font-size:0.7em">НАСТРОЙКИ</span>
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