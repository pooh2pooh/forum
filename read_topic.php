<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Read Topic</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="navbar.css">
</head>

<body>

	<main class="w-100 m-auto">

		<?php

		isset($_SESSION['AUTH']) ?: die('Error! Not Auth :(');


		if(isset($_POST['blocks'])) {

			require "stdin.class.php";
			$stdin = new stdin();

			require "parser.class.php";

			$stdin->CreatePost($_SESSION['AUTH'], $_GET['topic_id'], editorParser($_POST['blocks']));

		}


		require "stdout.class.php";
		$stdout = new stdout();


		$topic = $stdout->GetTopic($_GET['topic_id']);
		$posts = $stdout->ListPosts($_GET['topic_id']);

		?>

		<div class="px-5 pt-1 pb-3 mb-3 bg-dark text-white" style="height: 300px; background: url('<?php strlen($topic['cover']) > 0 ? print $topic['cover'] : print 'https://via.placeholder.com/2560'; ?>') #99f no-repeat center;">
			
		</div>

		<!-- Editor.js Modal -->
		<div class="modal fade" id="modalEditor" aria-hidden="true" aria-labelledby="ModalEditor" tabindex="-1">
			<div class="modal-dialog modal-fullscreen">

				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="ModalEditor">Сообщение:</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body" id="editorjs"></div>
					<div class="modal-footer">
						<button class="btn btn-primary btn-lg px-5 bg-primary bg-gradient" onclick="SendPost(<?=$_GET['topic_id']?>)"><i class="bi bi-send-fill mx-auto mb-1"></i> Отправить</button>
					</div>
				</div>


			</div>
		</div>

		<div class="container">
			<div class="row">

				<?php foreach ($posts as $row) {


					$curr_author = $row['author'];

					$sc_url_pattern = "/https:\/\/soundcloud.com\/\S*/";
					$sc_widget = "<iframe id='sc-widget' width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$1&show_artwork=true'></iframe>";


					// 
					// Когда вместо создания нового поста, отображается ДОПОЛНЕНО к предыдущему
					// 
					if(isset($prev_author) && !strcmp($prev_author, $curr_author)) { ?>

						<p class="py-3"><a href="#<?=$row['id']?>" style="color:#9f9f9f;text-decoration:none;">дополнено в __:__</a></p>

						<?=preg_replace_callback($sc_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>


					<?php 
					// 
					// Когда создаётся новый пост (НЕ ПЕРВЫЙ!)
					// 
					} else if(isset($prev_author) && strcmp($prev_author, $curr_author)) {

						$prev_author = $curr_author; ?>

					</div>
				</div>


				<div class="col-2"><img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;"></div>
				<div class="col-10 mb-1">
					<h5><?=$row['author']?> <small class="text-muted" style="font-size: 0.6em"><?=$row['date']?> <a href="#<?=$row['id']?>">#<?=$row['id']?></a></small></h5>

					<div class="lead p-4">

						<?=preg_replace_callback($sc_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>




						<?php
						// 
						// Когда создаётся новый пост (ПЕРВЫЙ!)
						// 
					} else {

						$prev_author = $curr_author; ?>


						<div class="col-2"><img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;"></div>
						<div class="col-10 mb-1">
							<h5><?=$row['author']?> <small class="text-muted" style="font-size: 0.6em"><?=$row['date']?> <a href="#<?=$row['id']?>">#<?=$row['id']?></a></small></h5>

							<div class="lead p-4">

								<?=preg_replace_callback($sc_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>

			<?php 
				// 
				// Здесь заканчивается цикл вывода постов
				// 
				}} ?>

							</div>
						</div>


					<hr class="pt-5">
				</main>


				<footer class="fixed-bottom bg-navbar">

					<div class="container">
						<div class="d-flex flex-wrap align-items-center justify-content-center">

							<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
								<li>
									<a href="forum.php" class="nav-link">
										<i class="bi bi-bookmarks-fill mx-auto mb-1" style="font-size: 2rem;"></i>
									</a>
								</li>
								<li>
									<a class="nav-link" data-bs-toggle="modal" href="#modalEditor" role="button">
										<i class="bi bi-send-plus mx-auto mb-1" style="font-size: 2rem;"></i>
									</a>
								</li>
								<li>
									<a href="#" class="nav-link">
										<i class="bi bi-fingerprint mx-auto mb-1" style="font-size: 2rem;"></i>
									</a>
								</li>
							</ul>
						</div>
					</div>

				</footer>

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
