<?php

	#
	# Если пользователь авторизован,
	# подключаем библиотеки для работы с базой данных
	require "lib/stdin.class.php";
	require "lib/stdout.class.php";


	#
	# Создаём экземпляры классов,
	# для записи и чтения базы данных
	$stdin = new stdin();
	$stdout = new stdout();