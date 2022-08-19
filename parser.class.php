<?php

function editorParser($src) {

				$data = '';

				foreach($src as $key => $value) {

								if(isset($src[$key]['data']['level'])) {

												switch($src[$key]['data']['level']) {
												case 2:
																$data .= '<h1>' . $src[$key]['data']['text'] . '</h1>';
																break;
												}

								} else {
												$data .= $src[$key]['data']['text'] . '<br>';
								}

				}

				return $data;
}
