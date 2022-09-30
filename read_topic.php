<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<?php

	#
	# Системный класс
	require 'lib/system.class.php';

	#
	# Получаем информацию о текущем топике
	$topic = $stdout->GetTopic($_GET['topic_id']);

?>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$topic['name']?></title>
	<link rel="stylesheet" href="css/navbar.css">
</head>

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require 'lib/auth.class.php';

	#
	# Получаем посты в топике
	$posts = $stdout->ListPosts($_GET['topic_id']);


	#
	# Сохраняем пост переданный из формы редактирования,
	# распарсиваем форматирование
	if(!empty($_POST['blocks']))
	{
		require "lib/parser.class.php";
		$stdin->CreatePost($_SESSION['AUTH'], $_GET['topic_id'], editorParser($_POST['blocks']));
	}

	#
	# Сохраняем изменённые данные топика
	if(!empty($_POST['topic_name']) && !empty($_POST['topic_first_post']))
	{
		$stdin->UpdateTopic($_GET['topic_id'], $_POST['topic_name'], $_POST['topic_first_post']);
	}
	if(!empty($_FILES['topic_cover']))
	{
		$upload_dir_cover = 'covers/';
		$cover = $upload_dir_cover . basename($_FILES['topic_cover']['name']);

		if (!move_uploaded_file($_FILES['topic_cover']['tmp_name'], $cover)) {
			die('Error! Cover not upload.');
		}

		$stdin->UpdateTopicCover($_GET['topic_id'], $cover);
	}

?>

<!-- ВЁРСТКА ТОПИКА -->
<body>
	<main class="w-100 bg-light m-auto" style="min-height: 1600px;">


		<!-- ОБЛОЖКА -->
		<div class="px-5 pt-1 pb-3 mb-3 bg-dark text-white" style="height: 300px; background: url('<?php !empty($topic['cover']) ? print 'covers/' . $topic['cover'] : print 'https://via.placeholder.com/2560'; ?>') #99f no-repeat center;">
			<?php if(!strcasecmp($_SESSION['AUTH'], $topic['author'])) { ?>
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
		<div class="container">
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

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container-fluid">
			<div class="d-flex flex-wrap align-items-center justify-content-center">
				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li class="text-center small">
						<a href="forum.php" class="nav-link">
							<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chat-square-text" viewBox="0 0 16 16">
							  <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
							  <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
							</svg>
							<br>
							<span style="font-size:0.7em">ТЕМЫ</span>
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
						<a href="#" class="nav-link">
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
						<button class="d-none d-md-block btn btn-lg btn-light border float-left" onclick="UpdateTopic(<?=$_GET['topic_id']?>);">Обновить</button>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

				</div>
				<div class="modal-body">

					<form class="m-1 m-md-5 mx-auto" id="UPDATE_TOPIC">
						<div class="mb-3">
							<label for="topicNameInput" class="form-label">Название</label>
							<input type="text" class="form-control" id="topicNameInput" name="NAME" placeholder="Топик про музыку" value="<?=$topic['name']?>">
						</div>
						<div class="mb-5">
							<label for="topicCoverInput" class="form-label">Обложка</label><br>
							<img class="img-fluid" src="<?php !empty($topic['cover']) ? print $topic['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$topic['name'] . ' cover'; ?>">
							<input type="file" class="form-control" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png">
						</div>
						<div class="mb-3">
							<label for="topicFirstPostInput" class="form-label">Тема</label>
							<textarea type="text" class="form-control" id="topicFirstPostInput" name="FIRSTPOST" placeholder="Описание или тема топика" rows="15"><?=$stdout->FirstPost($_GET['topic_id'])?></textarea>
						</div>


					</form>

				</div>
				<div class="d-block d-md-none modal-footer">
					<button class="btn btn-lg bg-light border" onclick="UpdateTopic(<?=$_GET['topic_id']?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Обновить</button>
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
						<button class="d-none d-md-block btn btn-light btn-lg border px-5 bg-gradient" onclick="SendPost(<?=$_GET['topic_id']?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Отправить</button>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
				</div>
				<div class="modal-body" id="editorjs"></div>
				<div class="d-block d-md-none modal-footer">
					<button class="btn btn-light btn-lg border px-5" onclick="SendPost(<?=$_GET['topic_id']?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Отправить</button>
				</div>
			</div>
		</div>
	</div>

	<link rel="stylesheet" href="css/bootstrap.min.css">
	<script src="js/bootstrap.bundle.min.js"></script>
	<script src="js/jquery-3.6.1.min.js"></script>
	<script src="js/editor.js"></script>
	<script src="js/editorjs.header.js"></script>
	<script src="js/editorjs.list.js"></script>
	<script src="js/editorjs.simple-image.js"></script>
	<script src="js/editor_settings.js"></script>
	<script src="js/copyToClipboard.js"></script>
	<script src="https://w.soundcloud.com/player/api.js" type="text/javascript"></script>


</body>
</html>

<?php require 'lib/print_runtime.php'; ?>