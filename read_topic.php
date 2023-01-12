<?php


	session_start();

	require 'lib/auth.class.php';
	require 'lib/system.class.php';


	#
	# Сохраняем пост переданный из формы редактирования,
	# распарсиваем форматирование в lib/parser.class.php
	#
	if(!empty($_POST['blocks'])) {
		require "lib/parser.class.php";
		$stdin->CreatePost($_SESSION['USER']['user_id'], $_GET['topic_id'], editorParser($_POST['blocks']));
	}

	#
	# Сохраняем изменённые данные топика
	#
	if(!empty($_POST['topic_name']) && !empty($_POST['topic_first_post']))
		$stdin->UpdateTopic($_GET['topic_id'], $_POST['topic_name'], $_POST['topic_first_post']);
	if(!empty($_FILES['topic_cover']['name']))
		$stdin->UpdateTopicCover($_GET['topic_id'], uploadCover($_FILES['topic_cover']));


	empty($_GET['topic_id']) ? $topic_id = 0 : $topic_id = intval($_GET['topic_id']);

	$topic = $stdout->getTopicInfo($topic_id, $_SESSION['USER']['username']); // информация о топике
	$posts = $stdout->getAllTopicPosts($topic_id); // посты

	if (empty($topic) || empty($posts))
		die(NOT_FOUND_TOPIC_ERROR_TEMPLATE);

	$data = [
		'topic' => $topic,
		'posts' => $posts
	];

	#
	# Отмечаем тему прочитанной,
	# т.е. убираем маркер непрочитанных сообщений
	if(!empty($_GET['read'])) $_SESSION['TOPIC_READ'][$topic_id] = true;

	$style->load('frontend/html/read_topic.html', $data);

?>
