<?php

	session_start();
	
	require 'lib/auth.class.php';
	require 'lib/system.class.php';

	#
	# Сохраняем изменённые данные пользователя
	if(!empty($_POST['username']) || !empty($_POST['lastfm']))
		$stdin->UpdateProfile($_GET['user_id'], $_POST['username'], $_POST['lastfm']);
	if(!empty($_FILES['user_avatar']['name']))
		$stdin->UpdateUserAvatar($_GET['user_id'], uploadCoverOrAvatar($_FILES['user_avatar']));

	$style->load('frontend/html/settings.html');

?>