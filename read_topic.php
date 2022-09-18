<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Read Topic</title>
	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="navbar.css">
</head>

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require "auth.class.php";

	#
	# Получаем информацию о текущем топике,
	# и посты в нём
	$topic = $stdout->GetTopic($_GET['topic_id']);
	$posts = $stdout->ListPosts($_GET['topic_id']);


	#
	# Сохраняем пост переданный из формы редактирования,
	# распарсиваем форматирование
	if(!empty($_POST['blocks']))
	{
		require "parser.class.php";
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
		<div class="px-5 pt-1 pb-3 mb-3 bg-dark text-white" style="height: 300px; background: url('<?php strlen($topic['cover']) > 0 ? print $topic['cover'] : print 'https://via.placeholder.com/2560'; ?>') #99f no-repeat center;">
			<?php if(!strcasecmp($_SESSION['AUTH'], $topic['author'])) { ?>
				<a class="btn btn-dark" data-bs-toggle="modal" href="#modalOptions" role="button"><i class="bi bi-pencil"></i> ИЗМЕНИТЬ</a>
			<?php } ?>
		</div>

		<!-- ПОСТЫ -->
		<div class="container p-md-5">
			<div class="row">

				<?php require "posts.inc.php"; ?>

			</div>
		</div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center">
				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li class="text-center"><a href="forum.php" class="nav-link"><i class="bi bi-house mx-auto mb-1" style="font-size: 2rem;"></i><br>ГЛАВНАЯ</a></li>
					<li class="text-center"><a class="nav-link" data-bs-toggle="modal" href="#modalEditor" role="button"><i class="bi bi-send-plus-fill mx-auto mb-1" style="font-size: 2rem;"></i><br>ЗАПОСТИТЬ</a></li>
					<li class="text-center"><a href="#" class="nav-link"><i class="bi bi-gear mx-auto mb-1" style="font-size: 2rem;"></i><br>НАСТРОЙКИ</a></li>
				</ul>
			</div>
		</div>
	</footer>


	<!-- Options Modal -->
	<div class="modal" id="modalOptions" aria-hidden="true" aria-labelledby="ModalEditor" tabindex="-1">
		<div class="modal-dialog modal-fullscreen">

			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="ModalEditor">Редактирование топика:</h5>

					<div class="d-grid gap-5 d-md-flex justify-content-md-end">
						<button class="d-none d-md-block btn btn-lg btn-light border float-left" onclick="UpdateTopic(<?=$_GET['topic_id']?>);">Обновить</button>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>

				</div>
				<div class="modal-body">

					<form class="w-75 mx-auto" id="UPDATE_TOPIC">
						<div class="mb-3">
							<label for="topicNameInput" class="form-label">Название</label>
							<input type="text" class="form-control" id="topicNameInput" name="NAME" placeholder="Топик про музыку" value="<?=$topic['name']?>">
						</div>
						<div class="my-4">
							<label for="topicCoverInput" class="form-label">Обложка</label><br>
							<img class="img-fluid" src="<?php strlen($topic['cover']) > 0 ? print $topic['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$topic['name'] . ' cover'; ?>">
							<input type="file" class="form-control" id="topicCoverInput" name="COVER" accept=".jpg,.jpeg,.png">
						</div>
						<div class="mb-3">
							<label for="topicFirstPostInput" class="form-label">Описание</label>
							<textarea type="text" class="form-control" id="topicFirstPostInput" name="FIRSTPOST" placeholder="Описание или тема топика" rows="15"><?=$stdout->FirstPost($_GET['topic_id'])?></textarea>
						</div>


					</form>

					<div class="w-75 mx-auto text-center">
						<p class="m-5 text-muted small">
							<strong>В названии</strong> только обычные символы<br>
							Обложка <strong>меньше 5мб в формате .jpg или .png</strong>
						</p>
					</div>

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
				<div class="modal-body" id="editorjs" style="height: 100%"></div>
				<div class="d-block d-md-none modal-footer">
					<button class="btn btn-light btn-lg border px-5" onclick="SendPost(<?=$_GET['topic_id']?>)" data-bs-dismiss="modal" data-bs-target="#modalEditor" aria-label="Close">Отправить</button>
				</div>
			</div>
		</div>
	</div>


	<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
	<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script>
	<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script>
	<script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script>
	<script src="editor.js"></script>
	<script src="https://w.soundcloud.com/player/api.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


</body>
</html>
