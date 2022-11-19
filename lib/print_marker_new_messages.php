<?php

	#
	# Считаем количество новых сообщений,
	# с момента посдней авторизации пользователя,
	# и выводим счётчик
	$marker = $stdout->NewMessages($row['id'], $_SESSION['USER']['last_login']);

	if ($marker && empty($_SESSION['TOPIC_READ'][$row['id']]))
	{

?>

		<span class="d-block d-md-none position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 98%">
			<span class="visually-hidden">Есть новые сообщения</span>
		</span>
		<span class="d-none d-md-block d-xl-none position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 98%">
			<span class="visually-hidden">Есть новые сообщения</span>
		</span>
		<span class="d-none d-xl-block position-absolute top-0 p-2 bg-danger border border-light rounded-circle" style="left: 99%">
			<span class="visually-hidden">Есть новые сообщения</span>
		</span>

<?php } ?>