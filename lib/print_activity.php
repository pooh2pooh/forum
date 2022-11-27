<?php if (!empty($_SESSION['USER']['login']) && !strcmp($_SESSION['USER']['login'], 'pooh')) { ?>
	<div class="pt-5">
		<ul class="list-group list-group-flush">
			<?php

				$activity = $stdout->GetActivity();

				foreach ($activity as $row)
				{
					$timestamp = new DateTime($row['timestamp'], new DateTimeZone('Europe/Moscow'));

			?>
		  <li class="list-group-item small"><span class="text-muted"><?=passed($timestamp)?></span> <?=$row['login'] . ' ' . $row['action']?></li>
			<?php } ?>
		</ul>
	</div>
<?php } ?>