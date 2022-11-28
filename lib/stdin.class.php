<?php


class stdin {

	protected PDO $db;

	function __construct()
	{

		require 'lib/Database.lib.php';

	}

	function CreateTopic($name, $post, $category, int $author, $cover)
	{

		$stmt = $this->db->prepare('INSERT INTO topics (name, author, category, cover) VALUES (:name, :author, :category, :cover)');
		$stmt->execute(['name' => $name, 'author' => $author, 'category' => $category, 'cover' => $cover]);
		$stmt_new_topic = $this->db->lastInsertId();
		$stmt_posts = $this->db->prepare('INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)');
		$stmt_posts->execute(['author' => $author, 'topic_id' => $stmt_new_topic, 'post' => $post]);
		return true;

	}

	function UpdateTopic($id, $name, $post)
	{

		$stmt = $this->db->prepare('UPDATE topics SET name = :name WHERE id = :id');
		$stmt->execute(['id' => $id, 'name' => $name]);
		$stmt_posts = $this->db->prepare('UPDATE posts SET post = :post WHERE topic_id = :id LIMIT 1');
		$stmt_posts->execute(['id' => $id, 'post' => $post]);
		return true;

	}
	function UpdateTopicCover($id, $cover)
	{

		$stmt = $this->db->prepare('UPDATE topics SET cover = :cover WHERE id = :id');
		$stmt->execute(['id' => $id, 'cover' => $cover]);
		return true;

	}

	function UpdateProfile($id, $username, $lastfm = '')
	{

		$stmt = $this->db->prepare('UPDATE users SET username = :username, lastfm_account = :lastfm WHERE id = :id');
		$stmt->execute(['id' => $id, 'username' => $username, 'lastfm' => $lastfm]);
		$_SESSION['USER']['username'] = $username;
		$_SESSION['USER']['lastfm_account'] = $lastfm;
		$activity = $this->db->prepare('INSERT INTO activity (login, action) VALUES (:login, :action)');
		$activity->execute(['username' => $username, 'action' => 'change profile']);
		return true;

	}
	function UpdateUserAvatar($id, $avatar)
	{

		$stmt = $this->db->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
		$stmt->execute(['id' => $id, 'avatar' => $avatar]);
		$_SESSION['USER']['avatar'] = $avatar;

		// very bad :(
		$tmp = $this->db->prepare('SELECT username FROM users WHERE id = :id');
		$tmp->execute(['id' => $id]);

		$activity = $this->db->prepare('INSERT INTO activity (login, action) VALUES (:login, :action)');
		$activity->execute(['username' => $tmp->fetchColumn(), 'action' => 'change avatar']);
		return true;

	}


	function CreatePost(int $author, $topic_id, $post)
	{
		$stmt = $this->db->prepare('INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)');
		return $stmt->execute(['author' => $author, 'topic_id' => $topic_id, 'post' => $post]);
	}

}
