<?php


	#
	# Проверяем авторизацию пользователя,
	# если нет, закрываем страницу с ошибкой
	isset($_SESSION['AUTH']) ?: die("<div class='text-center py-5'><a href='/'><img class='img-fluid' src='system-page-cover.png'></a><h1 class='pt-3'>Ты кто?</h1>Попробуй <a href='/'>авторизоваться</a></div><!-- Что ты здесь хотел увидеть ? -->");


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
