<?php session_start(); $startTime = new DateTime('now'); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Закрытый форум Харибда, тебе здесь нечего делать.">
	<title>Харибда</title>
	<link rel="stylesheet" href="css/navbar.css">
</head>

<body class="bg-light">
	<main class="w-100 m-auto">
		<div class="container-fluid pb-5">

			<?php

				#
				# Закрываем страницу для не авторизованных пользователей,
				# проверяет существование сессии
				#
				require 'lib/auth.class.php';

				#
				# Системный класс,
				# там некоторые общие функции вроде обработки даты и время,
				# функции загрузки и обработки изображений и пр.
				#
				require 'lib/system.class.php';

			?>

			<aside class="sticky-top d-none d-lg-block m-5 float-end" style="height: 100%; max-width: 25%;">
				<div class="list-group shadow-sm">
					<a class="list-group-item list-group-item-action px-5 bg-success bg-gradient text-white" href="create_topic.php">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="white" class="bi bi-bookmark-plus" viewBox="0 0 16 16">
								<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"/>
								<path d="M8 4a.5.5 0 0 1 .5.5V6H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V7H6a.5.5 0 0 1 0-1h1.5V4.5A.5.5 0 0 1 8 4z"/>
						</svg>
						Создать
					</a>
					
				</div>
				
				<?php require 'lib/print_myprofile.php';
					require 'lib/print_activity.php'; ?>
			</aside>

			<div class="row gap-1">

				<?php

					$topics = $stdout->getAllTopics();
					$curr_category = 0;

					foreach ($topics as $row) {

						$timestamp = passed($stdout->getLastTopicPostTime($row['id']));
						$author = $stdout->getUserNameByID($stdout->getLastPostAuthorID($row['id']));

						#
						# Печатаем названия категорий в списке тем
						if ($curr_category != $row['category']) {

							$category_name = $stdout->getCategoryName($row['category']);
							$curr_category = $row['category'];
							echo "<h3 class='ps-2 pt-4 ps-sm-4 pt-sm-3 fw-bold text-muted text-uppercase'>$category_name</h3>";

						}
				?>

						<div class="list-group">
							<a href="read_topic.php?topic_id=<?=$row['id']?><?php if ($stdout->isNewMessages($row['id'], $_SESSION['USER']['last_login']) && empty($_SESSION['TOPIC_READ'][$row['id']])) print '&read=true'; ?>" class="list-group-item list-group-item-action d-flex gap-2" aria-current="true">
								<img src="<?php !empty($row['cover']) ? print 'covers/thumbs/' . $row['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$row['name'] . ' обложка'; ?>" class="flex-shrink-0 rounded" width="94" height="94">
								<div class="d-flex flex-column w-100">
									<h6 class="d-flex mb-md-3">
										<span class="fw-bold"><?=$row['name']?></span>
										<span class="d-none d-md-block badge text-secondary text-bg-light ms-2"><?=$stdout->getCountTopicPosts($row['id'])?></span>

									</h6>
									<p class="d-block d-sm-none small p-0 m-0"><?=mb_strimwidth(strip_tags($stdout->getLastTopicPost($row['id']), '<br>'), 0, 24, '…')?></p>
									<p class="d-none d-sm-block d-md-none small p-0 m-0"><?=mb_strimwidth(strip_tags($stdout->getLastTopicPost($row['id']), '<br>'), 0, 96, '…')?></p>
									<p class="d-none d-md-block p-0 m-0"><?=mb_strimwidth(strip_tags($stdout->getLastTopicPost($row['id']), '<br>'), 0, 128, '…')?></p>

									<span class="small d-md-none text-muted"><?=$timestamp . ', ' . $author?>
									</span>
								</div>
								<div class="position-absolute text-end pe-2 end-0">
									
									<span class="d-none d-md-inline text-muted" style="font-size: 0.7em"><?=$timestamp . ', ' . $author?></span>

								</div>
								<?php require 'lib/print_marker_new_messages.php'; ?>
							</a>
						</div>
				<?php
					}
				?>

				<div class="pb-5">&nbsp;</div>
			</div>
		</div>
	</main>


	<footer class="fixed-bottom bg-navbar d-lg-none">
		<div class="container-fluid">
			<div class="d-flex">
				<ul class="nav col-12 my-1 my-md-0 text-small justify-content-evenly">
					<li class="text-center small"><a href="forum.php" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chat-square-text-fill" viewBox="0 0 16 16">
							<path d="M0 2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2h-2.5a1 1 0 0 0-.8.4l-1.9 2.533a1 1 0 0 1-1.6 0L5.3 12.4a1 1 0 0 0-.8-.4H2a2 2 0 0 1-2-2V2zm3.5 1a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h9a.5.5 0 0 0 0-1h-9zm0 2.5a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1h-5z"/>
						</svg>
						<br>
						<span style="font-size:0.7em">ФОРУМ</span>
					</a></li>
					<li class="text-center small"><a href="create_topic.php" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-bookmark-plus" viewBox="0 0 16 16">
							<path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5V2zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1H4z"/>
							<path d="M8 4a.5.5 0 0 1 .5.5V6H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V7H6a.5.5 0 0 1 0-1h1.5V4.5A.5.5 0 0 1 8 4z"/>
						</svg>
						<br>
						<span style="font-size:0.7em">СОЗДАТЬ</span>
					</a></li>
					<li class="text-center small"><a href="settings.php" class="nav-link">
						<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
							<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
							<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115l.094-.319z"/>
						</svg>
						<br>
						<span style="font-size:0.7em">НАСТРОЙКИ</span>
					</a></li>
				</ul>
			</div>
		</div>
	</footer>

	<link href="css/bootstrap.min.css" rel="stylesheet">
	<script src="js/bootstrap.bundle.min.js" defer></script>
</body>
</html>

<?php require 'lib/print_runtime.php'; ?>
