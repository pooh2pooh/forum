<?php

##
# ВНИМАНИЕ! ВНИМАНИЕ!
#
# В этом файле очень костыльная разметка (html),
# без необходимости, ЛУЧШЕ НЕ ТРОГАТЬ.
# Когда-нибудь отрефакторю. Обещаю.
# Спасибо за понимание.
#


$soundcloud_url_pattern	= "/https:\/\/soundcloud.com\/\S*/"; # Soundcloud виджет по ссылке


#
# Рендер постов
foreach ($posts as $row)
{

	$curr_author = $row['author']; # Автор текущего (в цикле) поста


	#
	# Когда создаётся новый пост (ПЕРВЫЙ!)
	if(empty($prev_author))
	{


		$prev_author = $curr_author; # Запоминаем автора поста, нужен для вывода следующего поста (функция ДОПОЛНЕНО)
?>


		<div class="d-none d-md-block col-md-2">
			<img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;">
		</div>
		<div class="col-12 col-md-10 mb-1">
			<h6>
				<strong><?=$row['author']?></strong>
				<small class="text-muted float-end" style="font-size: 0.6em"><?=date("d M Y H:i", strtotime($row['date']))?> 
					<a href="#<?=$row['id']?>" id="<?=$row['id']?>" title="Ссылка на пост">#<?=$row['id']?></a>
				</small>
			</h6>

			<div class="p-4 bg-white rounded-end shadow text-break">
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
			<img src="https://via.placeholder.com/120" class="img-fluid sticky-top" style="top: 10px;">
		</div>
		<div class="col-12 col-md-10 mb-1">
			<h6>
				<strong><?=$row['author']?></strong> 
				<small class="text-muted float-end" style="font-size: 0.6em"><?=date("d M Y H:i", strtotime($row['date']))?> 
					<a href="#<?=$row['id']?>" id="<?=$row['id']?>">#<?=$row['id']?></a>
				</small>
			</h6>

			<div class="p-4 bg-white rounded-end shadow text-break">
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
