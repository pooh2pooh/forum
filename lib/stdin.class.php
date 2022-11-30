<?php


	class stdin {

		protected PDO $db;

		function __construct()
		{
			require 'lib/Database.lib.php';
		}

		function CreateTopic(string $name, string $post, int $category, int $author, string $cover)
		{

			$stmt = $this->db->prepare('INSERT INTO topics (name, author, category, cover) VALUES (:name, :author, :category, :cover)');
			$stmt->execute(['name' => $name, 'author' => $author, 'category' => $category, 'cover' => $cover]);
			$stmt_new_topic = $this->db->lastInsertId();
			$stmt_posts = $this->db->prepare('INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)');
			$stmt_posts->execute(['author' => $author, 'topic_id' => $stmt_new_topic, 'post' => $post]);
			return true;

		}

		function UpdateTopic(int $id, string $name, string $post)
		{

			$stmt = $this->db->prepare('UPDATE topics SET name = :name WHERE id = :id');
			$stmt->execute(['id' => $id, 'name' => $name]);
			$stmt_posts = $this->db->prepare('UPDATE posts SET post = :post WHERE topic_id = :id LIMIT 1');
			$stmt_posts->execute(['id' => $id, 'post' => $post]);
			return true;

		}
		
		function UpdateTopicCover(int $id, string $cover)
		{

			$stmt = $this->db->prepare('UPDATE topics SET cover = :cover WHERE id = :id');
			$stmt->execute(['id' => $id, 'cover' => $cover]);
			return true;

		}

		function UpdateProfile(int $id, string $username, string $lastfm = '')
		{

			$stmt = $this->db->prepare('UPDATE users SET username = :username, lastfm_account = :lastfm WHERE id = :id');
			$stmt->execute(['id' => $id, 'username' => $username, 'lastfm' => $lastfm]);

			$_SESSION['USER']['username'] 			= $username;
			$_SESSION['USER']['lastfm_account'] = $lastfm;

			$activity = $this->db->prepare('INSERT INTO activity (username, action) VALUES (:username, :action)');
			$activity->execute(['username' => $username, 'action' => 'обновил(а) профиль']);
			return true;

		}

		function UpdateUserAvatar(int $id, string $avatar)
		{

			$stmt = $this->db->prepare('UPDATE users SET avatar = :avatar WHERE id = :id');
			$stmt->execute(['id' => $id, 'avatar' => $avatar]);
			$_SESSION['USER']['avatar'] = $avatar;

			$stmt2 = $this->db->prepare('SELECT username FROM users WHERE id = :id');
			$stmt2->execute(['id' => $id]);

			$activity = $this->db->prepare('INSERT INTO activity (username, action) VALUES (:username, :action)');
			$activity->execute(['username' => $stmt2->fetchColumn(), 'action' => 'обновил(а) аватарку']);
			return true;

		}

		function CreatePost(int $author, int $topic_id, string $post)
		{
			$stmt = $this->db->prepare('INSERT INTO posts (author, topic_id, post) VALUES (:author, :topic_id, :post)');
			return $stmt->execute(['author' => $author, 'topic_id' => $topic_id, 'post' => $post]);
		}

	}
