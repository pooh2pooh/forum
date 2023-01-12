<?php if (empty($_GET['user']) || strcmp($_SESSION['USER']['login'], $_GET['user'])) { ?>
<div class="py-5 text-center">
	<a class="text-dark bg-light" href="profile.php?user=<?=$_SESSION['USER']['login']?>">
		<?php $avatar = $this->getUserAvatarByID($_SESSION['USER']['user_id']); ?>
		<img src="<?php !empty($avatar) ? print 'uploads/avatars/thumbs/' . $avatar : print 'https://via.placeholder.com/150' ?>" class="img-fluid sticky-top" style="top: 10px;"><br>
		<?=$_SESSION['USER']['username']?>
	</a>
</div>
<?php } ?>