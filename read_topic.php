<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Read Topic</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>

	<main class="w-100 m-auto">

		<?php

isset($_SESSION['AUTH']) ?: die('Error! Not Auth :(');

if (isset($_POST['POST']) && isset($_GET['topic_id']) && strlen($_POST['POST'] >= 3)) {

	require "stdin.class.php";
	$stdin = new stdin();

	$stdin->CreatePost($_SESSION['AUTH'], $_GET['topic_id'], $_POST['POST']) ? header("Location: read_topic.php?topic_id=$_GET[topic_id]") : die('Error! Not Create Post :(');

}

require "stdout.class.php";
$stdout = new stdout();

$topic = $stdout->GetTopic($_GET['topic_id']);
$posts = $stdout->ListPosts($_GET['topic_id']);

?>

		<div class="px-5 pt-1 pb-3 mb-3 bg-dark text-white" style="height: 300px; background: url('<?php strlen($topic['cover']) > 0 ? print $topic['cover'] : print 'https://via.placeholder.com/2560'; ?>') #99f no-repeat center;">
			<!-- <h1 class="text-center"><?=$topic['name']?></h1>	
			<form action="read_topic.php?topic_id=<?=$_GET['topic_id']?>" method="post">
				<div class="mb-1">
					<textarea class="form-control border-0 bg-dark text-white" placeholder="Что ты хочешь сказать..." name="POST" rows="6"></textarea>
				</div>
				<div class="btn-group py-2" role="group" aria-label="Basic example">
					<button type="submit" class="btn btn-primary bg-primary bg-gradient btn-lg px-5"><i class="bi bi-send-fill mx-auto mb-1"></i> Отправить</button>
				</div>
			</form> -->
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
						<button class="btn btn-primary btn-lg px-5 bg-primary bg-gradient"><i class="bi bi-send-fill mx-auto mb-1"></i> Отправить</button>
					</div>
				</div>


			</div>
		</div>

		<?php

foreach ($posts as $row) {

?>

		<div class="list-group py-1 px-5">
			<a href="#" class="list-group-item list-group-item-action" aria-current="true">
				<div class="d-flex w-100 justify-content-between">
					<h5 class="mb-1 fw-bold"><?=$row['author']?></h5>
					<small class="text-muted"><?=$row['date']?></small>
				</div>

				<?php


	$sc_url_pattern = "/https:\/\/soundcloud.com\/\S*/";
	$sc_widget = "<iframe id='sc-widget' width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$1&show_artwork=true'></iframe>";


?>

				<p class="mb-1"><?=preg_replace_callback($sc_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?></p>
			</a>
		</div>

		<?php
}
?>

		<hr class="pt-5">
	</main>


	<footer class="fixed-bottom text-bg-dark">

		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center">

				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li>
						<a href="forum.php" class="nav-link text-white">
							<i class="bi bi-arrow-left-square-fill mx-auto mb-1" style="font-size: 2rem;"></i>
						</a>
					</li>
					<li>
						<a class="nav-link text-primary" data-bs-toggle="modal" href="#modalEditor" role="button">
							<i class="bi bi-send-plus mx-auto mb-1" style="font-size: 2rem;"></i>
						</a>
					</li>
					<li>
						<a href="#" class="nav-link text-white">
							<i class="bi bi-incognito mx-auto mb-1" style="font-size: 2rem;"></i>
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
	<script>
		const editor = new EditorJS({
			holder: 'editorjs',
			tools: {
				header: Header,
				list: List,
				image: SimpleImage
			},
		});
	</script>
	<script src="https://w.soundcloud.com/player/api.js" type="text/javascript"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
