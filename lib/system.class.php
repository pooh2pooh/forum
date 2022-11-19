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

				if (empty($upload_image['name'])) {
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

				if (webpConvert2($cover)) {

								$thumb->saveWEBP($cover_thumb . '.webp', 80);
								$filename = $filename . '.webp';

								unlink($cover_thumb);
								unlink($cover);

				}
				else
								$thumb->save($cover_thumb);

				return $filename;

}

#
# Функция для загрузки и обработки автара пользователя
function uploadAvatar(array $upload_image)
{

				if (empty($upload_image['name'])) {
								#
								# Обложка темы по-умолчанию (если не загружено)
								return 'haribda.jpg';
				}

				$filename = basename($upload_image['name']);
				$cover = 'avatars/' . $filename;
				$cover_thumb = 'avatars/thumbs/' . $filename;

				if ($upload_image['size'] > COVER_FILESIZE_MB*1000*1000)
								die('Максимальный размер аватара ' . COVER_FILESIZE_MB . 'MB');
				else if (!move_uploaded_file($upload_image['tmp_name'], $cover))
								die('Ошибка! Не получилось загрузить аватар, не является допустимым файлом или не может быть перемещен по какой-либо причине.');

				#
				# Класс для конвертации изображений в WEBP
				require 'lib/webpConvert2.class.php';

				#
				# Класс для создания миниатюры изображения
				require 'lib/thumbs.class.php';

				$thumb = new Thumbs($cover);
				$thumb->cut(150, 150);

				if (webpConvert2($cover)) {

								$thumb->saveWEBP($cover_thumb . '.webp', 80);
								$filename = $filename . '.webp';

								unlink($cover_thumb);
								unlink($cover);

				}
				else
								$thumb->save($cover_thumb);

				return $filename;

}

function passed(
				\DateTime $date,
				$time_format = 'H:i',
				// $month_format = 'd M в H:i',
				// $year_format = 'd.m.Y H:i'
) {

				$month_format = datefmt_create( 'ru_RU' ,IntlDateFormatter::FULL, IntlDateFormatter::FULL,
								'Europe/Moscow', IntlDateFormatter::GREGORIAN, 'd LLL в k:mm' );
				$year_format = datefmt_create( 'ru_RU' ,IntlDateFormatter::FULL, IntlDateFormatter::FULL,
								'Europe/Moscow', IntlDateFormatter::GREGORIAN, 'd LLL yyyy в k:mm' );

				$today = new \DateTime('now', $date->getTimezone());
				$yesterday = new \DateTime('-1 day', $date->getTimezone());
				$tomorrow = new \DateTime('+1 day', $date->getTimezone());
				$minutes_ago = round(($today->format('U') - $date->format('U')) / 60);
				$minutes_in = round(($date->format('U') - $today->format('U')) / 60);

				if ($minutes_ago > 0 && $minutes_ago < 60) {
								return sprintf('%s минут назад', $minutes_ago);

				} elseif ($minutes_in > 0 && $minutes_in < 60) {
								return sprintf('Через %s минут', $minutes_in);

				} elseif ($today->format('ymd') == $date->format('ymd')) {
								return sprintf('Сегодня в %s', $date->format($time_format));

				} elseif ($yesterday->format('ymd') == $date->format('ymd')) {
								return sprintf('Вчера в %s', $date->format($time_format));

				} elseif ($tomorrow->format('ymd') == $date->format('ymd')) {
								return sprintf('Завтра в %s', $date->format($time_format));

				} elseif ($today->format('Y') == $date->format('Y')) {
								// return $date->format($month_format);
								return datefmt_format($month_format, $date);

				} else {
								// return $date->format($year_format);
								return datedmt_format($year_format);
				}
}
