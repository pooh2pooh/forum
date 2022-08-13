<?php

// Write Here PHP Code!
class stdin {

	protected PDO $db;

	function __construct()
	{
		
		require "Database.lib.php";

	}

	function CreateTopic($name, $post, $author, $cover)
	{

		$stmt = $this->db->prepare("INSERT INTO topics (name, author, cover) VALUES (:name, :author, :cover)");
		$stmt->execute(['name' => $name, 'author' => $author, 'cover' => $cover]);
		$stmt_new_topic = $this->db->lastInsertId();
		$stmt_posts = $this->db->prepare("INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)");
		$stmt_posts->execute(['author' => $author, 'topic_id' => $stmt_new_topic, 'post' => $post]);
		return true;

	}

	function CreatePost($author, $topic_id, $post)
	{
		$stmt = $this->db->prepare("INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)");
		return $stmt->execute(['author' => $author, 'topic_id' => $topic_id, 'post' => $post]);
	}

}

?>

