<?php


	echo '<h1 class="pt-5 fw-bold">' . $data['username'] . '</h1>';

	if ($data['lastfm_account']) {

		$lastfm_api_key 	= $this->getConfig('lastfm_api_key');
		$lastfm_account 	= urlencode($data['lastfm_account']);
		$lastfm_api_query = curl_init("https://ws.audioscrobbler.com/2.0/?method=user.getrecenttracks&user=$lastfm_account&api_key=$lastfm_api_key&format=json&limit=5");
		curl_setopt($lastfm_api_query, CURLOPT_RETURNTRANSFER, true);

		if ( ($lastfm = curl_exec($lastfm_api_query) ) === false)
			echo 'Curl error: ' . curl_error($lastfm_api_query);
		else $lastfm = json_decode($lastfm, true);

		curl_close($lastfm_api_query);

		if (!strcmp($_SESSION['USER']['login'], $_GET['user']) 
				&& isset($lastfm['error']) 
				&& $lastfm['error'] == 6)
			echo '<h3 class="text-danger"><span class="fw-bold">lastfm:</span> не найдет логин ' . $lastfm_account . '</h3>';

		if (isset($lastfm['recenttracks']['track']) 
				&& count($lastfm['recenttracks']['track'])) {
			
			echo '<h3 class="ps-2">Недавно слушал:</h3>';
			echo '<ul class="list-group list-group-flush">';
			foreach ($lastfm['recenttracks']['track'] as $track) {
				if(!isset($track['date']['#text']))
					$timestamp = '<span class="small text-success">
													<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-play-fill" viewBox="0 0 16 16">
													  <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z"/>
													</svg>
													сейчас проигрывается
												</span>';
				else
					$timestamp = '<span class="text-muted text-nowrap" style="font-size: 0.7em">' . $track['date']['#text']  . '</span>';
				echo '<a class="list-group-item list-group-item-action fw-bold text-dark d-flex py-3 py-sm-2" href="' . $track['url'] . '" target="_blank">';
				echo '<span class="flex-fill text-break"><img class="px-2" src="' . $track['image'][0]['#text'] . '">'; // 0 - small, 1 - medium, 2 - large size for track cover
				echo $track['artist']['#text'] . ' — ' . $track['name'] . '</span>' . $timestamp . '</a>';
			}
			echo '</ul>';
		}

	}

?>