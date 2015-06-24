<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new My1DayServer\Application();
$app['debug'] = true;

$app->get('/messages', function () use ($app) {
    $messages = $app->getAllMessages();

    return $app->json($messages);
});

$app->get('/messages/{id}', function ($id) use ($app) {
    $message = $app->getMessage($id);

    return $app->json($message);
});

$app->post('/messages', function (Request $request) use ($app) {
    $data = $app->validateRequestAsJson($request);

    $username = isset($data['username']) ? $data['username'] : '';
    $body = isset($data['body']) ? $data['body'] : '';

	if(1 == preg_match('/^((今日)|(明日)|(明後日))の(.+の)?天気は？?$/u', $body)) {
		$split = mb_split('の', $body);
		$date = $split[0];
		if(count($array) == 3) {
			$city = $split[1];
		}
		
	}
    
    $createdMessage = $app->createMessage($username, $body, base64_encode(file_get_contents($app['icon_image_path'])));
	
    return $app->json($createdMessage);
});

$app->get('/weather/{dateLabel}/{city}', function ($dateLabel, $city) use ($app) {
	return $app->getWeather($dateLabel, $city);
});

$app->get('/init_weather', function () use ($app) {
	return $app->initWeatherCity();
});

$app->delete('/messages/{id}', function ($id) use ($app) {
    $app->deleteMessage($id);

    return new Response('', Response::HTTP_NO_CONTENT);
});

return $app;
