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

	#
	# Сохраняем изменённые данные пользователя
	if(!empty($_POST['username']) || !empty($_POST['lastfm']))
		$stdin->UpdateProfile($_GET['user_id'], $_POST['username'], $_POST['lastfm']);
	if(!empty($_FILES['user_avatar']['name']))
		$stdin->UpdateUserAvatar($_GET['user_id'], uploadAvatar($_FILES['user_avatar']));

?>

<!-- ВЁРСТКА СТРАНИЦЫ -->
<body>
	<main style="min-height: 1600px;">

		<!-- НАСТРОЙКИ -->
		<div class="container">
			<form action="create_topic.php" method="post" enctype="multipart/form-data">
				<div class="row my-5">
					<div class="col-md-8 mx-auto">

						<label class="h4 mb-3 fw-bold" for="userNameInput">Имя:</label>
						<input class="form-control form-control-lg border-0 mb-4" id="userNameInput" name="NAME" placeholder="Отображается везде" value="<?=$_SESSION['USER']['username']?>" autofocus>
						
						<label class="h5 mb-3" for="userAvatarInput">Аватар</label>
						<br>
						<img class="img-fluid" src="<?php !empty($_SESSION['USER']['avatar']) ? print 'avatars/thumbs/' . $_SESSION['USER']['avatar'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$_SESSION['USER']['username'] . ' avatar'; ?>">
						<input type="file" class="form-control border-0 mb-5" id="userAvatarInput" name="AVATAR" accept=".jpg,.jpeg,.png,.webp">

						<label class="h5 mb-3" for="lastfmInput">last.fm</label>
						<input class="form-control form-control-lg border-0 mb-4" id="lastfmInput" name="LASTFM_ACCOUNT" placeholder="Логин на сервисе" value="<?=$_SESSION['USER']['lastfm_account']?>">

						<div class="d-none d-lg-block">
							<div class="d-grid gap-2">
								<a href="#" class="btn btn-success bg-gradient btn-lg" onclick="this.classList.add('disabled'); UpdateProfile(<?=$_SESSION['USER']['user_id']?>)"><span>Сохранить</span>
								</a>
								<a href="#" class="btn btn-lg" onclick="history.go(-1); return false;">
									<span>Закрыть</span>
								</a>
							</div>
						</div>
						
					</div>
				</div>
			</form>

		</div>

	</main>


	<footer class="fixed-bottom bg-navbar d-lg-none">
		<div class="container-fluid">
			<div class="d-flex">
				<ul class="nav col-12 my-1 my-md-0 text-small justify-content-evenly">
					<li class="text-center small">
						<a href="#" class="nav-link" onclick="history.go(-1); event.preventDefault();">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="pink" class="bi bi-x" viewBox="0 0 16 16">
							  <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ЗАКРЫТЬ</span>
						</a>
					</li>
					<li class="text-center small">
						<a href="#" class="nav-link" onclick="UpdateProfile(<?=$_SESSION['USER']['user_id']?>)">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="lightgreen" class="bi bi-check-lg" viewBox="0 0 16 16">
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
	<script src="js/profile_update.js" defer></script>


</body>
</html>

<?php require 'lib/print_runtime.php'; ?>