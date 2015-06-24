<?php

namespace My1DayServer\Repository;

class WeatherCityRepository
{
    protected $conn;
    private $tableName = 'vg_weather_city';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

	/*
	 * vg_weather_cityの初期化＆データ挿入だからむやみに実行しない
	 */
	public function registCityFromXml() {
	
		$obj = $this->getPrimaryAreaXml();
		
		$this->deleteAllCity();
		foreach($obj['channel']['ldWeather_source']['pref'] as $pref) {
			if(array_values($pref['city']) === $pref['city']) { // 要素が一つだと(数値の)配列にならないので連想配列かどうかで判断
				foreach($pref['city'] as $city) {
					$this->createCity($city['@attributes']['id'], $city['@attributes']['title']);
				}
			}
			else {
				$city = $pref['city'];
				$this->createCity($city['@attributes']['id'], $city['@attributes']['title']);
			}
		}
	}
	
	private function getPrimaryAreaXml() {
		$xml = file_get_contents('http://weather.livedoor.com/forecast/rss/primary_area.xml');
		// xmlタグの名前空間(:で区切られるやつ)がうまくパース出来ないので_に置き換える
		$xml = preg_replace("/<([^>]+?):(.+?)>/", "<$1_$2>", $xml); 
		$xmlObject = simplexml_load_string($xml);
		return json_decode( json_encode($xmlObject), true);
	}

    public function getCity($title)
    {
        $sql = 'SELECT * FROM '.$this->tableName.' WHERE title = ?';
        $params = [$title];

        return $this->conn->fetchAssoc($sql, $params);
    }

    private function deleteAllCity()
    {
        $sql = 'DELETE FROM '.$this->tableName;
        $params = [];

        $this->conn->executeUpdate($sql, $params);
    }

    private function createCity($cityId, $cityTitle)
    {
        $data = array(
            'id' => $cityId,
            'title' => $cityTitle,
        );
        
        $queryResult = $this->conn->insert($this->tableName, $data);
        if (!$queryResult) {
            return false;
        }
        return true;
    }
}
