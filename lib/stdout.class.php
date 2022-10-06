<?php

// Write Here PHP Code!
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
						return !strcmp($profile['username'], $username) && !strcmp($profile['password'], $password);
		} else return false;

	}				

	function ListTopics()
	{

		$stmt = $this->db->query('SELECT * FROM topics ORDER BY category DESC');
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function GetTopic($id)
	{
		$stmt = $this->db->query("SELECT * FROM topics WHERE id = $id");
		return $stmt->fetch(PDO::FETCH_ASSOC);
	}

	function ListPosts($topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	function FirstPost($topic_id)
	{

		$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
		$stmt->execute(['topic_id' => $topic_id]);
		$post = $stmt->fetch(PDO::FETCH_LAZY);
		return preg_replace('/https:\/\/soundcloud.com\/\S*/', '&#127925;', $post['post']);
	}

	function getCategoryName($category_id)
	{
		$stmt = $this->db->query("SELECT name FROM categories WHERE id = $category_id");
		return $stmt->fetchColumn();
	}

}

?>

