### 投稿文で天気を聞く（様な投稿をする）と予報が投稿される
(update:2015/06/24)

イメージ

> 明日の東京の天気は？

と投稿すると、

>> 明日の東京の天気は曇りのち晴れです

がメッセージとして残るようになっています。

* 詳細説明

 受理するメッセージは`(今日・明日・明後日・[他]の)([地名]の)天気は？`  
 地名が見つからなければ（DBに登録されてなければ）予報はできないようになっています  
 日付で今日・明日・明後日以外が指定されるとその地域の予報概要文が投稿されます  
 日付と地名は順序逆でも受理できるようになっています。また、"は"／"？"は省略可です  
 
 天気予報の情報はLivedoorのお天気APIから取ってきてます
 http://weather.livedoor.com/forecast/webservice/json/v1?city=400040
 
* コードの要所

 メッセージを受理するかどうかは次のように調べています(app.php:27)  
 `preg_match('/^(.+の){1,2}天気(は？?)?$/u', $body)`  
 
 APIでは地域を指定するためにIDが必要となり、事前にDBに地名とIDを登録しておいたものから地名を指定してIDを取得しています  
 ただ、前述のとおり先に地名が来るか日付が来るかわからないので、地名がマッチするかを確かめてから決定しています。(Application.php:176-189)
 `
        $split = preg_split("/の/u", $message, -1, PREG_SPLIT_NO_EMPTY);
        $cityObj = $this['repository.weather']->getCity($split[0]);
		if($cityObj != null) {
			$dateLabel = $split[1];
		}
		else {
			$cityObj = $this['repository.weather']->getCity($split[1]);
			$dateLabel = $split[0];
			if($cityObj == null) {
				return 'ちょっとわかんないっすねー';
			}
		}`
 
 地名とIDの結び付けは以下参照との事でした。  
 http://weather.livedoor.com/forecast/rss/primary_area.xml  

 