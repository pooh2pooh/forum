<?php


	session_start();

	require 'lib/auth.class.php';
	require 'lib/system.class.php';


	empty($_GET['user']) ? $user = 'not_found' : $user = htmlspecialchars($_GET['user']);
	
	$profile = $stdout->getProfile($user, $_SESSION['USER']['username']);
	if (!$profile)
		die(NOT_FOUND_USER_ERROR_TEMPLATE);

	$style->load('frontend/html/profile.html', $profile);

?>