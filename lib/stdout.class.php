<?php


class stdout {

	protected PDO $db;

	function __construct()
	{

		require 'lib/Database.lib.php';

	}

	function Auth($username, $password)
	{

		$stmt = $this->db->prepare('SELECT * FROM users WHERE username = :username');
		$stmt->execute(['username' => $username]);
		$profile = $stmt->fetch(PDO::FETCH_LAZY);
		if ($profile) {
						if (!strcmp($profile['username'], $username) && !strcmp($profile['password'], $password)) {
							$_SESSION['USER']['username'] = $profile['username'];
							$_SESSION['USER']['last_login'] = $profile['last_login'];
							$stmt = $this->db->prepare('UPDATE users SET last_login = :last_login WHERE username = :username');
							$stmt->execute(['last_login' => $startTime, 'username' => $username]);
							return true;
						}
		} else return false;

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

	function ListPosts(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function FirstPost(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetch(PDO::FETCH_LAZY);
		return preg_replace('/https:\/\/soundcloud.com\/\S*/', '&#127925;', $post['post']);
	}
	function LastPost(int $topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetch(PDO::FETCH_LAZY);
		return preg_replace('/https:\/\/soundcloud.com\/\S*/', '&#127925;', $post['post']);
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

