<?php
	

	session_start();

	require 'lib/system.class.php';
	

	if (isset($_POST['LOGIN']) && isset($_POST['PASSWORD']) && !isset($_SESSION['USER']['username']))
		$stdout->Auth(md5($_POST['LOGIN']), $_POST['PASSWORD']) ?
            $style->load('frontend/html/forum.html') :
            $style->load('frontend/html/failed_auth.html');
	else {
    !isset($_SESSION['USER']['username']) ?
        $style->load('frontend/html/main_page.html') :
        $style->load('frontend/html/forum.html');
  }

?>