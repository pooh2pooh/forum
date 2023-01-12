<?php if (!strcmp($_SESSION['USER']['login'], 'b158274419d6e6dc4c1088cacfd49283')) { ?>
	<div class="pt-5 overflow-scroll" style="max-height: 500px;">
		<ul class="list-group list-group-flush">
			<?php

				$activity = $this->GetActivity();

				if ($activity) {
					foreach ($activity as $row)
					{
						$timestamp = new DateTime($row['timestamp'], new DateTimeZone('Europe/Moscow'));

				?>
			  <li class="list-group-item small"><span class="text-muted"><?=passed($timestamp)?></span> <?=$row['username'] . ' ' . $row['action']?></li>
			<?php } } ?>
		</ul>
	</div>
<?php } ?>