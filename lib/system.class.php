<?php

	#
	#
	define('COVER_FILESIZE_MB', 8); # MB

	#
	# Если пользователь авторизован,
	# подключаем библиотеки для работы с базой данных
	require 'lib/stdin.class.php';
	require 'lib/stdout.class.php';


	#
	# Создаём экземпляры классов,
	# для записи и чтения базы данных
	$stdin = new stdin();
	$stdout = new stdout();


	#
	# Функция для загрузки и обработки обложки
	function uploadCover(array $upload_image)
	{

		if (empty($upload_image['name']))
		{
			#
			# Обложка темы по-умолчанию (если не загружено)
			return 'haribda.jpg';
		}

		$filename = basename($upload_image['name']);
		$cover = 'covers/' . $filename;
		$cover_thumb = 'covers/thumbs/' . $filename;

		if ($upload_image['size'] > COVER_FILESIZE_MB*1000*1000)
			die('Максимальный размер обложки ' . COVER_FILESIZE_MB . 'MB');
		else if (!move_uploaded_file($upload_image['tmp_name'], $cover))
			die('Ошибка! Не получилось загрузить обложку, не является допустимым файлом или не может быть перемещен по какой-либо причине.');

		#
		# Класс для конвертации изображений в WEBP
		require 'lib/webpConvert2.class.php';

		#
		# Класс для создания миниатюры изображения
		require 'lib/thumbs.class.php';
		
		$thumb = new Thumbs($cover);
		$thumb->cut(150, 150);

		if (webpConvert2($cover))
		{

			$thumb->saveWEBP($cover_thumb . '.webp', 80);
			$filename = $filename . '.webp';

			unlink($cover_thumb);
			unlink($cover);

		}
		else
			$thumb->save($cover_thumb);

		return $filename;

	}