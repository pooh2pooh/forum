<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Форум</title>
  	<link rel="stylesheet" href="bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
	<link rel="stylesheet" href="navbar.css">

</head>

<body>

	<main class="w-100 bg-light m-auto pb-5" style="min-height: 1600px;">

<?php

	#
	# Закрываем страницу для не авторизованных пользователей
	require "auth.class.php";

	$topics = $stdout->ListTopics();

	foreach ($topics as $row)
	{

?>

		<div class="list-group p-2 mx-sm-5">
			<a href="read_topic.php?topic_id=<?=$row['id']?>" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
			<img src="<?php strlen($row['cover']) > 0 ? print $row['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$row['name'] . ' cover'; ?>" class="flex-shrink-0" width="128" height="128">
				<div class="d-flex gap-2 w-100 justify-content-between">
					<div>
						<h6 class="mb-0 fw-bold"><?=$row['name']?></h6>
						<p class="mb-0 opacity-75"><?=mb_substr($stdout->FirstPost($row['id']), 0, 120)?></p>
						<small class="d-md-none opacity-50 text-nowrap"><?=date("d M Y H:i", strtotime($row['date'])) . ', ' . $row['author']?></small>
					</div>
					<small class="d-none d-md-block opacity-50 text-nowrap"><?=date("d M Y H:i", strtotime($row['date'])) . ', ' . $row['author']?></small>
				</div>
			</a>
		</div>
<?php
	}
?>

		<div class="pb-5"></div>

	</main>


	<footer class="fixed-bottom bg-navbar">
		<div class="container-fluid">
			<div class="d-flex flex-wrap align-items-center justify-content-center">
				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li class="text-center small"><a href="forum.php" class="nav-link"><i class="bi bi-house-fill mx-auto mb-1" style="font-size: 1.3rem;"></i><br>ГЛАВНАЯ</a></li>
					<li class="text-center small"><a href="create_topic.php" class="nav-link"><i class="bi bi-bookmark-plus mx-auto mb-1" style="font-size: 1.3rem;"></i><br>НОВЫЙ ТОПИК</a></li>
					<li class="text-center small"><a href="#" class="nav-link"><i class="bi bi-gear mx-auto mb-1" style="font-size: 1.3rem;"></i><br>НАСТРОЙКИ</a></li>
				</ul>
			</div>
		</div>
	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
