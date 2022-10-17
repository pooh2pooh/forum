<?php


class stdout {

	protected PDO $db;

	function __construct()
	{

		require 'lib/Database.lib.php';

	}

	function Auth($login, $password)
	{

		$stmt = $this->db->prepare('SELECT * FROM users WHERE login = :login');
		$stmt->execute(['login' => $login]);
		$profile = $stmt->fetch(PDO::FETCH_LAZY);
		if ($profile) {
						if (!strcmp($profile['login'], $login) && !strcmp($profile['password'], $password)) {
							$_SESSION['USER']['user_id'] = $profile['id'];
							$_SESSION['USER']['login'] = $profile['login'];
							$_SESSION['USER']['username'] = $profile['username'];
							$_SESSION['USER']['avatar'] = $profile['avatar'];
							$_SESSION['USER']['last_login'] = $profile['last_login'];
							$stmt = $this->db->prepare('UPDATE users SET last_login = :last_login WHERE login = :login');
							$stmt->execute(['last_login' => $startTime, 'login' => $login]);
							return true;
						}
		} else return false;

	}

	function getUserNameByID(int $user_id)
	{
		$stmt = $this->db->prepare('SELECT username FROM users WHERE id = :user_id');
		$stmt->execute(['user_id' => $user_id]);
		return $stmt->fetchColumn();
	}

	function getLoginByID(int $user_id)
	{
		$stmt = $this->db->prepare('SELECT login FROM users WHERE id = :user_id');
		$stmt->execute(['user_id' => $user_id]);
		return $stmt->fetchColumn();
	}

	function getUserAvatarByID(int $user_id)
	{
		$stmt = $this->db->prepare('SELECT avatar FROM users WHERE id = :user_id');
		$stmt->execute(['user_id' => $user_id]);
		return $stmt->fetchColumn();
	}

	function getLastPostAuthor(int $topic_id)
	{
		$stmt = $this->db->prepare('SELECT author FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
		$stmt->execute(['topic_id' => $topic_id]);
		$user_id = $stmt->fetchColumn();
		return $user_id;
	}

	function NewMessages(int $topic_id, $timestamp)
	{
		$stmt = $this->db->prepare('SELECT date FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
		$stmt->execute(['topic_id' => $topic_id]);
		$post_timestamp = $stmt->fetchColumn();
		if (strtotime($post_timestamp) > strtotime($timestamp))
			return true;
		else return false;
	}

	function ListTopics()
	{

		$stmt = $this->db->query('SELECT * FROM topics ORDER BY category DESC');
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function GetTopic(int $topic_id)
	{

		$stmt = $this->db->query("SELECT * FROM topics WHERE id = $topic_id");
		return $stmt->fetch(PDO::FETCH_ASSOC);

	}

	function CountPosts(int $topic_id)
	{
		$stmt = $this->db->prepare('SELECT count(*) FROM posts WHERE topic_id = :topic_id');
		$stmt->execute(['topic_id' => $topic_id]);
		return $stmt->fetchColumn();
	}
	function ListPosts(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function FirstPost(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT post FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetch(PDO::FETCH_LAZY);
		return preg_replace('/https:\/\/soundcloud.com\/\S*/', '&#127925;', $post['post']);
	}
	function LastPost(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT post FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetch(PDO::FETCH_LAZY);
		return preg_replace('/https:\/\/soundcloud.com\/\S*/', '&#127925;', $post['post']);
	}
	function LastPostTimestamp(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT date FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetchColumn();
		$passed = new DateTime($post, new DateTimeZone('Europe/Moscow'));
		return $passed;
	}

	function ListCategories()
	{
		$stmt = $this->db->query('SELECT * FROM categories ORDER BY id DESC');
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function getCategoryName(int $category_id)
	{
		$stmt = $this->db->query("SELECT name FROM categories WHERE id = $category_id");
		return $stmt->fetchColumn();
	}

}

?>

