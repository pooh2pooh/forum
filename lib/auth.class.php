<?php

	define('NOT_AUTH_ERROR_TEMPLATE', '<meta name="viewport" content="width=device-width, initial-scale=1"><title>Харибда</title><link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Ты кто?</h1>Попробуй <a href="/">авторизоваться</a></div><!-- Что ты здесь хотел увидеть ? -->');
	define('NOT_FOUND_TOPIC_ERROR_TEMPLATE', '<meta name="viewport" content="width=device-width, initial-scale=1"><title>Тема не найдена</title><link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такой темы</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');
	define('NOT_FOUND_USER_ERROR_TEMPLATE', '<meta name="viewport" content="width=device-width, initial-scale=1"><title>Профиль не найден</title><link rel="stylesheet" href="css/bootstrap.min.css"><div class="text-center py-5"><a href="/"><img class="img-fluid" src="system-page-cover.png.webp"></a><h1 class="pt-3">Нет такого пользователя</h1>Попробуй <a href="/">вернуться на форум</a></div><!-- Что ты здесь хотел увидеть ? -->');

	isset($_SESSION['USER']['username']) ?: die(NOT_AUTH_ERROR_TEMPLATE);
