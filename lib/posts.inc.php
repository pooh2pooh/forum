<?php

##
# ВНИМАНИЕ! ВНИМАНИЕ!
#
# В этом файле очень костыльная разметка (html),
# без необходимости, ЛУЧШЕ НЕ ТРОГАТЬ.
# Когда-нибудь отрефакторю. Обещаю.
# Спасибо за понимание.
#


$soundcloud_url_pattern	= '/https:\/\/soundcloud.com\/\S*/'; # Soundcloud виджет по ссылке

function isMyPost($author) {
	##
	# Выделяем посты автора для него самого
	#
	return strcmp($author, $_SESSION['AUTH']);
}

#
# Рендер постов
foreach ($posts as $row)
{

	$curr_author = $row['author']; # Автор текущего (в цикле) поста
	$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; # Текущий URL
	$url = explode('#', $url);


	#
	# Когда создаётся новый пост (ПЕРВЫЙ!)
	if(empty($prev_author))
	{


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)
?>

		<div class="text-center mb-5">
			<button type="button" class="btn" id="copyToClipboardButton" title="Ссылка на тему">тема создана <strong><?=date("d M Y в H:i", strtotime($row['date']))?></strong></button>
		</div>

		<div class="d-none d-md-block col-md-2">
			<?php if(isMyPost($row['author'])) { ?>
				<img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;">
			<?php } ?>
		</div>
		<div class="col-12 col-md-10 mb-1">
			<?php if(isMyPost($row['author'])) { ?>
				<h6>
					<strong class="h2 fw-bold"><?=$row['author']?></strong>
					
				</h6>
			<?php } ?>

			<div class="p-3 p-md-4 <?=isMyPost($row['author']) ? 'bg-white' : 'bg-light'?> rounded-5 shadow text-break">
				<?=preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>


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
			<?php if(isMyPost($row['author'])) { ?>
				<img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;">
			<?php } ?>
		</div>
		<div class="col-12 col-md-10 mb-1">
			<?php if(isMyPost($row['author'])) { ?>
				<h6>
					<strong class="h2 fw-bold"><?=$row['author']?></strong>
					
				</h6>
			<?php } ?>

			<div class="p-md-4 <?=isMyPost($row['author']) ? 'bg-white' : 'bg-light'?> rounded-5 shadow text-break">
				<small class="text-muted float-end" style="font-size: 0.8em"><?=date("d M Y H:i", strtotime($row['date']))?> 
					<a href="#<?=$row['id']?>" id="<?=$row['id']?>">#<?=$row['id']?></a>
				</small><br><br>
				<?=preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>


<?php
	#
	# Когда вместо создания нового поста, отображается ДОПОЛНЕНО к предыдущему
	} else {


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)
?>

		<p class="text-end" style="font-size: 0.8em"><a href="#<?=$row['id']?>" style="color:#cfcfcf;text-decoration:none;" id="<?=$row['id']?>">дополнено <?=date("d M Y H:i", strtotime($row['date']))?></a></p>

		<?=preg_replace_callback($soundcloud_url_pattern, function ($matches) { return "<iframe width='100%' height='166' scrolling='no' frameborder='no' src='https://w.soundcloud.com/player/?url=$matches[0]&show_artwork=true'></iframe>"; }, $row['post'])?>


<?php
	}
#
# Здесь заканчивается цикл вывода постов
}
?>

</div>

<div style="margin: 100px 0;"></div>
