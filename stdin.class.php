<?php


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

	function UpdateTopic($id, $name, $post)
	{

					$stmt = $this->db->prepare("UPDATE topics SET name = :name WHERE id = :id");
					$stmt->execute(['id' => $id, 'name' => $name]);
					$stmt_posts = $this->db->prepare("UPDATE posts SET post = :post WHERE topic_id = :id LIMIT 1");
					$stmt_posts->execute(['id' => $id, 'post' => $post]);
					return true;

	}

	function UpdateTopicCover($id, $cover)
	{

					$stmt = $this->db->prepare("UPDATE topics SET cover = :cover WHERE id = :id");
					$stmt->execute(['id' => $id, 'cover' => $cover]);
					return true;

	}


	function CreatePost($author, $topic_id, $post)
	{
					$stmt = $this->db->prepare("INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)");
					return $stmt->execute(['author' => $author, 'topic_id' => $topic_id, 'post' => $post]);
	}

}
