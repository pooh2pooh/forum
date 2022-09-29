<?php

function editorParser($src) {

	$data = '';

	foreach($src as $key => $value) 
	{

		if(!empty($src[$key]['data']['level'])) 
		{

			switch($src[$key]['data']['level']) 
			{
				case 1:
					$data .= '<h1>' . $src[$key]['data']['text'] . '</h1>';
					break;
				case 2:
					$data .= '<h2>' . $src[$key]['data']['text'] . '</h2>';
					break;
				case 3:
					$data .= '<h3>' . $src[$key]['data']['text'] . '</h3>';
					break;
				case 4:
					$data .= '<h4>' . $src[$key]['data']['text'] . '</h4>';
					break;
				case 5:
					$data .= '<h5>' . $src[$key]['data']['text'] . '</h5>';
					break;
				case 6:
					$data .= '<h6>' . $src[$key]['data']['text'] . '</h6>';
					break;
			}

		} 
		else if(!empty($src[$key]['data']['style']))
		{

			switch($src[$key]['data']['style'])
			{
				case 'ordered':
					$data .= '<ol>';
					foreach ($src[$key]['data']['items'] as $value) {
						$data .= '<li>' . $value . '</li>';
					}
					$data .= '</ol>';
					break;
				case 'unordered':
					$data .= '<ul>';
					foreach ($src[$key]['data']['items'] as $value) {
						$data .= '<li>' . $value . '</li>';
					}
					$data .= '</ul>';
					break;
			}

		} 
		else 
		{
			$data .= $src[$key]['data']['text'] . '<br>';
		}

	}

	return $data;
}
