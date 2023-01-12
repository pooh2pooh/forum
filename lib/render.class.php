<?php


	class Style extends stdout
	{
		protected $cache_dir = "frontend/html/cache/"; // Путь к директории кэша 
		protected $cache_ext = ".cache";               // Расширение для кэша

			
			// Загрузка
		function load ($path, $data = []) {
			$name = basename ($path);
			if (file_exists($this->cache_dir . $name . $this->cache_ext))
				include_once ($this->cache_dir . $name . $this->cache_ext);
			else $this->compile($path, $name, $data);
		}		
			
			// Компиляция
		function compile ($path, $name, $data) {
			$file = file_get_contents ($path);
			$result = $this->replace($file, $data);
			file_put_contents ($this->cache_dir . $name . $this->cache_ext, '<?php $startTime = new DateTime(\'now\'); ?>' . $result);
			include_once ($this->cache_dir . $name . $this->cache_ext);
		}
			
			// Замена переменных (блоков)
			// динамического контента
		function replace ($template, $data) {
			$url = $_SERVER['REQUEST_URI'];
			$url = explode('?', $url);
			$url = $url[0];
			if (!strcmp($url, '/index.php') || !strcmp($url, '/')) {
				$matches = array (
					"{MY_PROFILE_BUTTON}" => file_get_contents ('frontend/html/inc/my_profile.inc.php'),
					"{ACTIVITY_BLOCK}" => file_get_contents ('frontend/html/inc/activity.inc.php'),
					"{TOPIC_LIST}" => file_get_contents ('frontend/html/inc/topic_list.inc.php'),
					"{RUNTIME}" => file_get_contents ('frontend/html/inc/runtime.inc.php'),
				);
			} elseif (!strcmp($url, '/create_topic.php')) {
				$matches = array (
					"{CATEGORY_SELECT}" => file_get_contents ('frontend/html/inc/category_select.inc.php'),
					"{RUNTIME}" => file_get_contents ('frontend/html/inc/runtime.inc.php'),
				);
			} elseif (!strcmp($url, '/read_topic.php')) {
				$matches = array (
					"{MY_PROFILE_BUTTON}" => file_get_contents ('frontend/html/inc/my_profile.inc.php'),
					"{EDIT_PROFILE_BUTTON}" => file_get_contents ('frontend/html/inc/edit_profile_button.inc.php'),
					"{ACTIVITY_BLOCK}" => file_get_contents ('frontend/html/inc/activity.inc.php'),
					"{COVER_TOPIC}" => file_get_contents ('frontend/html/inc/cover_topic.inc.php'),
					"{TITLE_TOPIC}" => '<?=$data[\'topic\'][\'name\']?>',
					"{PATH_TO_COVER_TOPIC}" => '<?=!empty($data[\'topic\'][\'cover\']) ? \'uploads/covers/thumbs/\' . $data[\'topic\'][\'cover\'] : \'https://via.placeholder.com/150\'?>',
					"{FIRST_POST_FOR_TOPIC}" => '<?=$this->getFirstTopicPost($data[\'topic\'][\'id\'])?>',
					"{POSTS}" => file_get_contents ('frontend/html/inc/posts.inc.php'),
					"{PROFILE}" => file_get_contents ('frontend/html/inc/profile.inc.php'),
					"{RUNTIME}" => file_get_contents ('frontend/html/inc/runtime.inc.php'),
				);
			} elseif (!strcmp($url, '/profile.php')) {
				$matches = array (
					"{MY_PROFILE_BUTTON}" => file_get_contents ('frontend/html/inc/my_profile.inc.php'),
					"{ACTIVITY_BLOCK}" => file_get_contents ('frontend/html/inc/activity.inc.php'),
					"{EDIT_PROFILE_BUTTON}" => file_get_contents ('frontend/html/inc/edit_profile_button.inc.php'),
					"{USERNAME}" => '<?=$data[\'username\']?>',
					"{PROFILE}" => file_get_contents ('frontend/html/inc/profile.inc.php'),
					"{RUNTIME}" => file_get_contents ('frontend/html/inc/runtime.inc.php'),
				);
			} elseif (!strcmp($url, '/settings.php')) {
				$matches = array (
					"{RUNTIME}" => file_get_contents ('frontend/html/inc/runtime.inc.php'),
				);
			} else {
				$matches = array (
					"{переменная}" => "значение",
				);
			}
			$result = str_replace (array_keys($matches), array_values($matches), $template);
			return $result;
		}
			
			// Очистка кэша
		function update () {
			$handle = opendir ($this->cache_dir);
			while (false !== ($file = readdir($handle))) {
				if ($file !== "." and $file !== "..")
					$result = unlink ($this->cache_dir.$file);
			}
			closedir ($handle);
			return $result;
		}
	}

	$style = new Style(); // Определение объекта
	
	if (isset($_GET['clean_cache']))
		$style->update();