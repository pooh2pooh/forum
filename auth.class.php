<?php


	#
	# Проверяем авторизацию пользователя,
	# если нет, закрываем страницу с ошибкой
	isset($_SESSION['AUTH']) ?: die('Error! Not Auth :(');


	#
	# Если пользователь авторизован,
	# подключаем библиотеки для работы с базой данных
	require "stdin.class.php";
	require "stdout.class.php";


	#
	# Создаём экземпляры классов,
	# для записи и чтения базы данных
	$stdin = new stdin();
	$stdout = new stdout();
