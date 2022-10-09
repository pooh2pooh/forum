<?php

	#
	# Считаем количество новых сообщений,
	# с момента посдней авторизации пользователя,
	# и выводим счётчик
	$marker = $stdout->NewMessages($row['id'], $_SESSION['USER']['last_login']);

	if ($marker && empty($_SESSION['TOPIC_READ'][$row['id']]))
	{

?>

		<span class="d-block d-md-none position-absolute top-0 translate-middle badge rounded-pill bg-danger text-danger" style="left: 97%">&bull;</span>
		<span class="d-none d-md-block position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-danger">&bull;</span>

<?php } ?>