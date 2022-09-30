<?php

	#
	# Следующая переменная должна быть в объявлена,
	# в самом начале скрипта где собираемся измерять
	# время выполнения:
	#
	# $startTime = new DateTime('now');

	$endTime = new DateTime('now');
	$runTime = $startTime->diff($endTime);
	if (!empty($_SESSION['AUTH']) && !strcmp($_SESSION['AUTH'], 'pooh'))
		echo $runTime->format('<span style="background:red;color:white;position:fixed;top:0;right:0">%S секунд, %f микросекунд</span>');
