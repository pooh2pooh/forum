<?php

$server_db	= 'localhost';
$name_db	= 'test';
$user_db	= 'forum_user_db';
$password_db	= 'qwe123';

try {
				$this->db = new PDO("mysql:host=$server_db;dbname=$name_db", $user_db, $password_db);
} catch (PDOException $e) {
				print "Error!: " . $e->getMessage();
				die();
}

?>
