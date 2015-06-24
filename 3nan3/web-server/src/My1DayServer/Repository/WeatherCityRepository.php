<?php

namespace My1DayServer\Repository;

class WeatherCityRepository
{
    protected $conn;
    private $dbName = 'vg_weather_city';

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

	public function registCityFromXml() {
		$obj = $this->getPrimaryAreaXml();
		
		
		
		return $obj;
	}
	
	private function getPrimaryAreaXml() {
		//$xmlObject = simplexml_load_file('http://weather.livedoor.com/forecast/rss/primary_area.xml');
		$xml = file_get_contents('http://weather.livedoor.com/forecast/rss/primary_area.xml');
		// xmlタグの名前空間(:で区切られるやつ)がうまくパース出来ないので_に置き換える
		$xml = preg_replace("/<([^>]+?):(.+?)>/", "<$1_$2>", $xml); 
		$xmlObject = simplexml_load_string($xml);
		//$xmlArray = json_encode( $xmlObject->rss->channel->chldren('ldWeather', true)->source );
		return json_encode($xmlObject->channel->ldWeather_source);
		/*$ch = curl_init();
		$url = "http://weather.livedoor.com/forecast/rss/primary_area.xml";
		$options = array(
			CURLOPT_URL => $url,
			CURLOPT_HEADER => false,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 60, // TODO 失敗した場合どうなるのか
			CURLOPT_CUSTOMREQUEST => "GET",
			);
		curl_setopt_array($ch, $options);
		$response = json_encode(curl_exec($ch)) ;
		curl_close($ch);
		return $response;*/
	}

    private function isExistingCity($title)
    {
        $sql = 'SELECT id FROM '.$dbName.' WHERE title = ?';
        $params = [$title];

        return (bool)$this->conn->fetchColumn($sql, $params);
    }

    private function getAllCity()
    {
        $sql = 'SELECT * FROM '.$dbName;

        return $this->conn->fetchAll($sql);
    }

    private function getCity($title)
    {
        $sql = 'SELECT * FROM '.$dbName.' WHERE title = ?';
        $params = [$title];

        return $this->conn->fetchAssoc($sql, $params);
    }

    private function deleteCity($id)
    {
        $sql = 'DELETE FROM '.$dbName.' WHERE id = ?';
        $params = [$id];

        $this->conn->executeUpdate($sql, $params);
    }

    /*private function createCity($cityId, $cityTitle)
    {
        $data = array_merge('id' => $cityId,
            'title' => $cityTitle,
        ]);

        $queryResult = $this->conn->insert($dbName, $data);
        if (!$queryResult) {
            return false;
        }
        return true;
    }*/
}
