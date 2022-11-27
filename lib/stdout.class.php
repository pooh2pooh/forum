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
							$_SESSION['USER']['lastfm_account'] = $profile['lastfm_account'];
							$stmt = $this->db->prepare('UPDATE users SET last_login = :last_login WHERE login = :login');
							$stmt->execute(['last_login' => $startTime, 'login' => $login]);
							$activity = $this->db->prepare('INSERT INTO activity (login, action, timestamp) VALUES (:login, :action, :timestamp)');
							$activity->execute(['login' => $login, 'action' => 'auth', 'timestamp' => $startTime]);
							return true;
						}
		} else return false;

	}

	function GetActivity()
	{

		$stmt = $this->db->query('SELECT * FROM activity ORDER BY id DESC');
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function GetConfig($name)
	{
		// Возвращает значение настройки по имени
		$stmt = $this->db->prepare("SELECT value FROM config WHERE name = :name");
		$stmt->execute(['name' => $name]);
		return $stmt->fetchColumn();

	}

	function GetProfile(string $username, string $who = 'Haribda')
	{
		// Возвращает массив со всеми данными профиля из базы
		$stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
		$stmt->execute(['username' => $username]);
		$activity = $this->db->prepare('INSERT INTO activity (login, action) VALUES (:login, :action)');
		$activity->execute(['login' => $who, 'action' => 'view profile ' . $username]);
		return $stmt->fetch(PDO::FETCH_ASSOC);

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

	function GetTopic(int $topic_id, string $who = 'Haribda')
	{

		$stmt = $this->db->query("SELECT * FROM topics WHERE id = $topic_id");
		$activity = $this->db->prepare('INSERT INTO activity (login, action) VALUES (:login, :action)');
		$activity->execute(['login' => $who, 'action' => 'view topic id' . $topic_id]);
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
		return preg_replace(['/https:\/\/soundcloud.com\/\S*/', '/https:\/\/youtu.be\/\S*/', '/https:\/\/music.youtube.com\/\S*/'], '&#127925;', $post['post']);
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

