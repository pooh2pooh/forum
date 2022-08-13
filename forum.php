<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Forum</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
</head>

<body>

	<main class="w-100 m-auto">

<?php

isset($_SESSION['AUTH']) ?: die('Error! Not Auth :(');

require "stdout.class.php";
$stdout = new stdout();

$topics = $stdout->ListTopics();

foreach ($topics as $row) {

?>

		<div class="list-group">
			<a href="read_topic.php?topic_id=<?=$row['id']?>" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
			<img src="<?php strlen($row['cover']) > 0 ? print $row['cover'] : print 'https://via.placeholder.com/150' ?>" alt="<?=$row['name'] . ' cover'; ?>" class="flex-shrink-0" width="128" height="128">
				<div class="d-flex gap-2 w-100 justify-content-between">
					<div>
						<h6 class="mb-0"><?=$row['name']?></h6>
						<p class="mb-0 opacity-75"><?=$stdout->FirstPost($row['id'])?></p>
					</div>
					<small class="opacity-50 text-nowrap"><?=$row['date'] . ', ' . $row['author']?></small>
				</div>
			</a>
		</div>
<?php
}
?>
	</main>


	<footer class="fixed-bottom text-bg-dark">

		<div class="container">
			<div class="d-flex flex-wrap align-items-center justify-content-center">

				<ul class="nav col-12 my-1 justify-content-around my-md-0 text-small">
					<li>
						<a href="forum.php" class="nav-link text-danger">
							<i class="bi bi-bookmarks-fill mx-auto mb-1" style="font-size: 2rem;"></i>
						</a>
					</li>
					<li>
						<a href="create_topic.php" class="nav-link text-white">
							<i class="bi bi-bookmark-plus-fill mx-auto mb-1" style="font-size: 2rem;"></i>
						</a>
					</li>
					<li>
						<a href="#" class="nav-link text-white">
							<i class="bi bi-incognito mx-auto mb-1" style="font-size: 2rem;"></i>
						</a>
					</li>
				</ul>
			</div>
		</div>

	</footer>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
</body>

</html>
