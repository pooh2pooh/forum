<?php

	#
	# Следующая переменная должна быть в объявлена,
	# в самом начале скрипта где собираемся измерять
	# время выполнения:
	#
	# $startTime = new DateTime('now');

	$endTime = new DateTime('now');
	$runTime = $startTime->diff($endTime);
	if (!strcmp($_SESSION['USER']['login'], 'b158274419d6e6dc4c1088cacfd49283'))
		echo $runTime->format('<span style="background:red;color:white;position:fixed;top:0;right:0;opacity:0.4;">%S секунд, %f микросекунд</span>');

?>