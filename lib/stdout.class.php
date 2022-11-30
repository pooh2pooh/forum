<?php


	class stdout
	{

		protected PDO $db;

		function __construct()
		{
			require 'lib/Database.lib.php';
		}

		function Auth(string $login, string $password)
		{

			$stmt = $this->db->prepare('SELECT * FROM users WHERE login = :login');
			$stmt->execute(['login' => $login]);
			$profile = $stmt->fetch(PDO::FETCH_LAZY);
			if ($profile) {
				if (!strcmp($profile['login'], $login) && !strcmp($profile['password'], $password)) {

					$_SESSION['USER']['user_id'] 				= $profile['id'];
					$_SESSION['USER']['login'] 					= $profile['login'];
					$_SESSION['USER']['username'] 			= $profile['username'];
					$_SESSION['USER']['avatar'] 				= $profile['avatar'];
					$_SESSION['USER']['last_login'] 		= $profile['last_login'];
					$_SESSION['USER']['lastfm_account'] = $profile['lastfm_account'];

					$stmt = $this->db->prepare('UPDATE users SET last_login = :last_login WHERE login = :login');
					$stmt->execute(['last_login' => $startTime, 'login' => $login]);

					$activity = $this->db->prepare('INSERT INTO activity (username, action) VALUES (:username, :action)');
					$activity->execute(['username' => $profile['username'], 'action' => 'авторизовался']);

					return true;
				} else return false;
			} else return false;
		}

		function getActivity()
		{
			$stmt = $this->db->query('SELECT * FROM activity ORDER BY id DESC');
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function getConfig(string $name)
		{
			// Возвращает значение настройки по имени
			$stmt = $this->db->prepare("SELECT value FROM config WHERE name = :name");
			$stmt->execute(['name' => $name]);
			return $stmt->fetchColumn();
		}

		function getProfile(string $login, string $who = 'Haribda')
		{
			// Возвращает массив со всеми данными профиля из базы
			$stmt = $this->db->prepare("SELECT * FROM users WHERE login = :login");
			$stmt->execute(['login' => $login]);
			$profile = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($profile) {
				$username = $profile['username'];
				$activity = $this->db->prepare('INSERT INTO activity (username, action) VALUES (:username, :action)');
				$activity->execute(['username' => $who, 'action' => 'смотрел(а) профиль ' . $username]);
			}

			return $profile;
		}

		function getUserNameByID(int $id)
		{
			$stmt = $this->db->prepare('SELECT username FROM users WHERE id = :id');
			$stmt->execute(['id' => $id]);
			return $stmt->fetchColumn();
		}

		function getLoginByID(int $id)
		{
			$stmt = $this->db->prepare('SELECT login FROM users WHERE id = :id');
			$stmt->execute(['id' => $id]);
			return $stmt->fetchColumn();
		}

		function getUserAvatarByID(int $id)
		{
			$stmt = $this->db->prepare('SELECT avatar FROM users WHERE id = :id');
			$stmt->execute(['id' => $id]);
			return $stmt->fetchColumn();
		}

		function getLastPostAuthorID(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT author FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
			$stmt->execute(['topic_id' => $topic_id]);
			$user_id = $stmt->fetchColumn();
			return $user_id;
		}

		function getAllTopics()
		{
			$stmt = $this->db->query('SELECT * FROM topics ORDER BY category DESC');
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function getTopicInfo(int $topic_id, string $who = 'Haribda')
		{
			$stmt = $this->db->prepare("SELECT * FROM topics WHERE id = :topic_id");
			$stmt->execute(['topic_id' => $topic_id]);
			$topic = $stmt->fetch(PDO::FETCH_ASSOC);

			if ($topic)
 			{
 				$topic_name = $topic['name'];
 				$activity = $this->db->prepare('INSERT INTO activity (username, action) VALUES (:username, :action)');
				$activity->execute(['username' => $who, 'action' => 'смотрел(а) тему ' . $topic_name]);
			}

			return $topic;
		}

		function isNewMessages(int $topic_id, $timestamp)
		{
			$stmt = $this->db->prepare('SELECT date FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
			$stmt->execute(['topic_id' => $topic_id]);
			$post_timestamp = $stmt->fetchColumn();
			if (strtotime($post_timestamp) > strtotime($timestamp))
				return true;
			else return false;
		}

		function getCountTopicPosts(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT count(*) FROM posts WHERE topic_id = :topic_id');
			$stmt->execute(['topic_id' => $topic_id]);
			return $stmt->fetchColumn();
		}
		function getAllTopicPosts(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT * FROM posts WHERE topic_id = :topic_id ORDER BY id');
			$stmt->execute(['topic_id' => $topic_id]);
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function getFirstTopicPost(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT post FROM posts WHERE topic_id = :topic_id ORDER BY id');
			$stmt->execute(['topic_id' => $topic_id]);
			$post = $stmt->fetch(PDO::FETCH_LAZY);
			return preg_replace(['/https:\/\/soundcloud.com\/\S*/', '/https:\/\/youtu.be\/\S*/', '/https:\/\/music.youtube.com\/\S*/'], '&#127925;', $post['post']);
		}
		function getLastTopicPost(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT post FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
			$stmt->execute(['topic_id' => $topic_id]);
			$post = $stmt->fetch(PDO::FETCH_LAZY);
			return preg_replace(['/https:\/\/soundcloud.com\/\S*/', '/https:\/\/youtu.be\/\S*/', '/https:\/\/music.youtube.com\/\S*/'], '&#127925;', $post['post']);
		}
		function getLastTopicPostTime(int $topic_id)
		{
			$stmt = $this->db->prepare('SELECT date FROM posts WHERE topic_id = :topic_id ORDER BY id DESC');
			$stmt->execute(['topic_id' => $topic_id]);
			$post = $stmt->fetchColumn();
			$passed = new DateTime($post, new DateTimeZone('Europe/Moscow'));
			return $passed;
		}

		function getAllCategories()
		{
			$stmt = $this->db->query('SELECT * FROM categories ORDER BY id DESC');
			return $stmt->fetchAll(PDO::FETCH_ASSOC);
		}

		function getCategoryName(int $category_id)
		{
			$stmt = $this->db->prepare("SELECT name FROM categories WHERE id = :category_id");
			$stmt->execute(['category_id' => $category_id]);
			return $stmt->fetchColumn();
		}

	}

?>
