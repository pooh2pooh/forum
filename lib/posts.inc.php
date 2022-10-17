<?php

##
# ВНИМАНИЕ! ВНИМАНИЕ!
#
# В этом файле очень костыльная разметка (html),
# без необходимости, ЛУЧШЕ НЕ ТРОГАТЬ.
# Когда-нибудь отрефакторю. Обещаю.
# Спасибо за понимание.
#


$soundcloud_url_pattern	= '/https:\/\/soundcloud.com\/\S*/'; 		# Soundcloud виджет по ссылке
$youtube_url_pattern	= '/https:\/\/youtu.be\/\S*/'; 	 		 	 		# YouTube виджет по ссылке
$ytmusic_url_pattern	= '/https:\/\/music.youtube.com\/\S*/'; 	# YouTube Music виджет по ссылке

function isMyPost(string $login) {
	##
	# Выделяем посты автора для него самого
	#
	return !strcmp($login, $_SESSION['USER']['login']);
}

#
# Рендер постов
foreach ($posts as $row)
{

	$login = $stdout->getLoginByID($row['author']);
	$username = $stdout->getUserNameByID($row['author']);
	$timestamp = new DateTime($row['date'], new DateTimeZone('Europe/Moscow'));
	$avatar = $stdout->getUserAvatarByID($row['author']);
	$curr_author = $login; # Автор текущего (в цикле) поста
	$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; # Текущий URL
	$url = explode('#', $url);


	#
	# Когда создаётся новый пост (ПЕРВЫЙ!)
	if(empty($prev_author))
	{


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)
?>

		<div class="text-center mb-5">
			<button type="button" class="btn" id="copyToClipboardButton" title="Копировать сылку на тему в буфер обмена">тема создана <strong><?=passed($timestamp)?></strong></button>
		</div>

		<div class="d-none d-md-block col-md-2">
			<?php if(!isMyPost($login)) { ?>
				<img src="<?php !empty($avatar) ? print 'avatars/thumbs/' . $avatar : print 'https://via.placeholder.com/150' ?>" class="img-fluid sticky-top" style="top: 10px;">
			<?php } ?>
		</div>
		<div class="col-12 col-md-10 mb-1">
			<?php if(!isMyPost($login)) { ?>
				<h6>
					<img src="<?php !empty($avatar) ? print 'avatars/thumbs/' . $avatar : print 'https://via.placeholder.com/150' ?>" class="d-inline d-md-none" width="32px;">
					<strong class="h2 fw-bold align-middle"><?=$username?></strong>
					
				</h6>
			<?php } ?>

			<div class="p-3 p-md-4 <?=isMyPost($login) ? 'bg-light' : 'bg-white'?> rounded-5 shadow text-break">
				<?php $row['post'] = preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>

				<?php $row['post'] = preg_replace_callback($ytmusic_url_pattern, function ($matches) { $url = stristr(substr(strip_tags(stristr($matches[0], '=')), 1), '&', true); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>

				<?=preg_replace_callback($youtube_url_pattern, function ($matches) { $url = substr(strip_tags(strrchr($matches[0], '/')), 1); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>


<?php
	#
	# Когда создаётся новый пост (НЕ ПЕРВЫЙ!)
	} else if(!empty($prev_author) && strcasecmp($prev_author, $curr_author)) {


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)

?>

			</div>
		</div>

		<div class="pt-5"></div>

		<div class="d-none d-md-block col-md-2">
			<?php if(!isMyPost($login)) { ?>
				<img src="<?php !empty($avatar) ? print 'avatars/thumbs/' . $avatar : print 'https://via.placeholder.com/150' ?>" class="img-fluid sticky-top" style="top: 10px;">
			<?php } ?>
		</div>

		<div class="col-12 col-md-10 mb-1">
			<?php if(!isMyPost($login)) { ?>
				<h6>
					<img src="<?php !empty($avatar) ? print 'avatars/thumbs/' . $avatar : print 'https://via.placeholder.com/150' ?>" class="d-inline d-md-none" width="32px;">
					<strong class="h2 fw-bold align-middle"><?=$username?></strong>
					
				</h6>
			<?php } ?>

			<div class="p-3 p-md-4 <?=isMyPost($login) ? 'bg-light' : 'bg-white'?> rounded-5 shadow text-break">
				<small class="text-muted float-end" style="font-size: 0.8em"><?=passed($timestamp)?> 
					<a href="#<?=$row['id']?>" id="<?=$row['id']?>">#<?=$row['id']?></a>
				</small><br><br>
				<?php $row['post'] = preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>

				<?php $row['post'] = preg_replace_callback($ytmusic_url_pattern, function ($matches) { $url = strip_tags(stristr($matches[0], '=', true)); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>

				<?php $row['post'] = preg_replace_callback($ytmusic_url_pattern, function ($matches) { $url = stristr(substr(strip_tags(stristr($matches[0], '=')), 1), '&', true); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>

				<?=preg_replace_callback($youtube_url_pattern, function ($matches) { $url = substr(strip_tags(strrchr($matches[0], '/')), 1); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>


<?php
	#
	# Когда вместо создания нового поста, отображается ДОПОЛНЕНО к предыдущему
	} else {


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)
?>

		<p class="text-end" style="font-size: 0.8em"><a href="#<?=$row['id']?>" style="color:#cfcfcf;text-decoration:none;" id="<?=$row['id']?>">дополнено <?=passed($timestamp)?></a></p>

		<?php $row['post'] = preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>

		<?php $row['post'] = preg_replace_callback($ytmusic_url_pattern, function ($matches) { $url = stristr(substr(strip_tags(stristr($matches[0], '=')), 1), '&', true); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>

		<?=preg_replace_callback($youtube_url_pattern, function ($matches) { $url = substr(strip_tags(strrchr($matches[0], '/')), 1); return "<iframe width='100%' height='315' src='https://www.youtube-nocookie.com/embed/$url' title='YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>"; }, $row['post'])?>


<?php
	}
#
# Здесь заканчивается цикл вывода постов
}
?>

</div>

<div style="margin: 100px 0;"></div>
