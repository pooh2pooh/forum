<?php


	session_start();

	require 'lib/auth.class.php';
	require 'lib/system.class.php';

	if (!empty($_POST['NAME']) && !empty($_POST['POST']) && !empty($_POST['CATEGORY']))
	{

		$stdin->CreateTopic($_POST['NAME'], $_POST['POST'], $_POST['CATEGORY'], $_SESSION['USER']['user_id'], uploadCoverOrAvatar($_FILES['COVER']), false) ? header('Location: /') : die('Ошибка! Не получилось создать новую тему :(');

	}

	$style->load('frontend/html/create_topic.html');

?>