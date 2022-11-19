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
	# Сохраняем пост переданный из формы редактирования,
	# распарсиваем форматирование в lib/parser.class.php
	if(!empty($_POST['blocks']))
	{
		require "lib/parser.class.php";
		$stdin->CreatePost($_SESSION['USER']['user_id'], $_GET['topic_id'], editorParser($_POST['blocks']));
	}

	#
	# Сохраняем изменённые данные топика
	if(!empty($_POST['topic_name']) && !empty($_POST['topic_first_post']))
		$stdin->UpdateTopic($_GET['topic_id'], $_POST['topic_name'], $_POST['topic_first_post']);
	if(!empty($_FILES['topic_cover']['name']))
		$stdin->UpdateTopicCover($_GET['topic_id'], uploadCover($_FILES['topic_cover']));


	#
	# Закрываем страницу, если не указан ID темы
	empty($_GET['topic_id']) ? $topic_id = 0 : $topic_id = intval($_GET['topic_id']);
	if ($topic_id)
	{
		#
		# Получаем информацию о текущем топике
		$topic = $stdout->GetTopic($topic_id);
		#
		# Получаем посты в топике
		$posts = $stdout->ListPosts($topic_id);
		if (empty($topic) || empty($posts)) die('<link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такой темы</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');
	} else die('<link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такой темы</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');

	#
	# Отмечаем тему прочитанной,
	# т.е. убираем маркер непрочитанных сообщений
	if(!empty($_GET['read'])) $_SESSION['TOPIC_READ'][$topic_id] = true;

?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Закрытый форум Харибда, <?=htmlspecialchars($stdout->FirstPost($topic['id']))?>">
	<title><?=$topic['name']?></title>
	<link rel="stylesheet" href="css/navbar.css">
</head>


<!-- ВЁРСТКА ТОПИКА -->
<body class="bg-light">
	<main class="w-100 m-auto">


		<!-- ОБЛОЖКА -->
		<div class="px-5 pt-1 pb-3 mb-3 bg-dark text-white" style="height: 300px; background: url('<?php !empty($topic['cover']) ? print 'covers/' . $topic['cover'] : print 'https://via.placeholder.com/2560'; ?>') #99f no-repeat center;">
			<?php if(!strcasecmp($_SESSION['USER']['login'], $stdout->getLoginByID($topic['author']))) { ?>
				<a class="btn btn-dark" data-bs-toggle="modal" href="#modalOptions" role="button">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
						<path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
					</svg>
					ИЗМЕНИТЬ
				</a>
			<?php } ?>
			<p class="lead"><?=$topic['name']?></p>
		</div>

		<!-- ПОСТЫ -->
		<div class="container-fluid" style="position:absolute;">

			<aside class="sticky-top d-none d-lg-block m-5 float-end" style="height: 100%;">
					<div class="list-group shadow-sm">
						<a class="list-group-item list-group-item-action px-5 bg-success bg-gradient text-white" data-bs-toggle="modal" href="#modalEditor" role="button">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
							  <path d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z"/>
							</svg>
							Запостить
						</a>
						<a class="list-group-item list-group-item-action px-5 bg-light" href="forum.php">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
							  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
							</svg>
							Форум
						</a>
						<a class="list-group-item list-group-item-action px-5 bg-light" href="settings.php">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
								  <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
								  <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
								</svg>
							Настройки
						</a>
					</div>
					<div class="list-group shadow-sm pt-2">
						<a class="list-group-item list-group-item-action px-5 bg-light" href="#">
							<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-up" viewBox="0 0 16 16">
							  <path fill-rule="evenodd" d="M8 15a.5.5 0 0 0 .5-.5V2.707l3.146 3.147a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 1 0 .708.708L7.5 2.707V14.5a.5.5 0 0 0 .5.5z"/>
							</svg>
							Вверх
						</a>
					</div>
				</aside>

			<div class="row">

				<?php require 'lib/posts.inc.php'; ?>

				<div class="toast-container position-fixed top-0 end-0 p-3">
				  <div id="copyToClipboard" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
				    <div class="toast-header">
				      <!-- <img src="..." class="rounded me-2" alt="..."> -->
				      <strong class="me-auto">Сцилла</strong>
				      <small>Сейчас</small>
				      <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
				    </div>
				    <div class="toast-body">
				      Ссылка на тему скопирована в буфер обмена
				    </div>
				  </div>
				</div>

			</div>
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
						<a class="nav-link" data-bs-toggle="modal" href="#modalEditor" role="button">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-send-fill" viewBox="0 0 16 16">
							  <path d="M15.964.686a.5.5 0 0 0-.65-.65L.767 5.855H.766l-.452.18a.5.5 0 0 0-.082.887l.41.26.001.002 4.995 3.178 3.178 4.995.002.002.26.41a.5.5 0 0 0 .886-.083l6-15Zm-1.833 1.89L6.637 10.07l-.215-.338a.5.5 0 0 0-.154-.154l-.338-.215 7.494-7.494 1.178-.471-.47 1.178Z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ЗАПОСТИТЬ</span>
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


	<!-- Options Modal -->
	<div class="modal" id="modalOptions" aria-hidden="true" aria-labelledby="ModalEditor" tabindex="-1">
		<div class="modal-dialog modal-fullscreen">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalEditor">Редактирование темы:</h5>

					<div class="d-grid gap-5 d-md-flex justify-content-md-end">
						<button class="d-none d-md-block btn btn-lg btn-light border float-left" onclick="UpdateTopic(<?=$topic_id?>);">Обновить</button>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

				</div>
				<div class="modal-body">

					<form id="UPDATE_TOPIC">
						<div class="row">
							<div class="col-md-8 mx-auto">
								<label class="h4 mb-3 fw-bold" for="topicNameInput">Название темы:</label>
								<input type="text" class="form-control form-control-lg border-0 mb-4" id="topicNameInput" name="NAME" placeholder="Должно точно отражать суть" value="<?=$topic['name']?>">

								<label class="h4 mb-3 fw-bold" for="topicFirstPostInput">Краткое описание:</label>
								<textarea type="text" class="form-control form-control-lg border-0 mb-4" id="topicFirstPostInput" name="FIRSTPOST" placeholder="Можно использовать стандартные html теги для оформления" rows=12><?=$stdout->FirstPost($topic_id)?></textarea>

								<label class="h5 mb-3" for="topicCoverInput">Обложка (необязательно)</label><br>
								<img class="img-fluid" src="<?php !empty($topic['cover']) ? print 'covers/thumbs/' . $topic['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$topic['name'] . ' обложка'; ?>">
								<input type="file" class="form-control border-0 mb-5" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png,.webp">

							</div>
						</div>
					</form>

				</div>
				<div class="d-block d-md-none modal-footer">
					<button class="btn btn-lg bg-light border" onclick="UpdateTopic(<?=$topic_id?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Обновить</button>
				</div>
			</div>
		</div>
	</div>	

	<!-- Editor.js Modal -->
	<div class="modal" id="modalEditor" aria-hidden="true" aria-labelledby="ModalEditor" tabindex="-1">
		<div class="modal-dialog modal-fullscreen">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalEditor">Сообщение:</h5>
					<div class="d-grid gap-5 d-md-flex justify-content-md-end">
						<button class="d-none d-md-block btn btn-light btn-lg border px-5 bg-gradient" onclick="SendPost(<?=$topic_id?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Отправить</button>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				</div>
				<div class="modal-body" id="editorjs"></div>
				<div class="d-block d-md-none modal-footer">
					<button class="btn btn-light btn-lg border px-5" onclick="SendPost(<?=$topic_id?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Отправить</button>
				</div>
			</div>
		</div>
	</div>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/jquery-3.6.1.min.js" defer></script>
	<script src="js/bootstrap.bundle.min.js" defer></script>
	<script src="js/editor.js" defer></script>
	<script src="js/editorjs.header.js" defer></script>
	<script src="js/editorjs.list.js" defer></script>
	<script src="js/editorjs.simple-image.js" defer></script>
	<script src="js/editor_settings.js" defer></script>
	<script src="js/copyToClipboard.js" defer></script>
	<script src="js/player/api.js" type="text/javascript" defer></script>
</body>
</html>

<?php require 'lib/print_runtime.php'; ?>