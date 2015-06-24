<?php

namespace My1DayServer;

class Weather
{
	public function forecast($dateLabel, $city) {	
		$city_id = 400040; // DBか何かで$cityにマッチしたID引っ張ってくる
		
		$obj = $this->getWeather($city_id);
		if($obj == null) { return 'ちょっとわかんないですねー'; }
		
		$message = null;
		foreach($obj['forecasts'] as $forecast) {
			if($forecast['dateLabel'] == $dateLabel) {
				$message = $dateLabel."の".$city."の天気は".$forecast['telop']."でしょう";
			}
		}
		if($message == null) {
			$message = $obj['description']['text'];
		}
		
		return $message;
	}
	
	public function getWeather($city_id) {
		$ch = curl_init();
		$url = "http://weather.livedoor.com/forecast/webservice/json/v1";
		$options = array(
			CURLOPT_URL => $url.'?city='.$city_id,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60, // TODO 失敗した場合どうなるのか
			CURLOPT_CUSTOMREQUEST => "GET",
			);
		curl_setopt_array($ch, $options);
		$response = json_decode(curl_exec($ch), true);
		curl_close($ch);
		return $response;
	}
}