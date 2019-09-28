<?php

require_once("api/api.php");

$url = $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
$userIp = $_SERVER['REMOTE_ADDR'];

if ($url == strstr($url, "short-link.ru/api/")) {
	$apiObj = new ApiMethods();
	if ($url == strstr($url,"short-link.ru/api/short/")) {
		if ($url == "short-link.ru/api/short" or $url == "short-link.ru/api/short/") {
			echo "Впишите ссылку для сокращения в адресную строку после short/";
			return;
		}
		$longLink = explode("short-link.ru/api/short/", $url, 2);
		$longLink = $longLink[1];
		$apiObj->makeShort($userIp, $longLink);
	} else if ($url == "short-link.ru/api/links" or $url == "short-link.ru/api/links/") {
		$apiObj->showLinks($userIp);
	} else if ($url == "short-link.ru/api/stat" or $url == "short-link.ru/api/stat/") {
		$apiObj->showStatistics($userIp);
	} else echo "Ошибка! Неизвестный запрос к API";
	
} else if ($url == strstr($url, "short-link.ru") and strlen($url) == 19) {
	$shortLink = "http://" . $url;
	$apiObj = new ApiMethods();
	$apiObj->redirect($shortLink);
} else if ($url == "short-link.ru" or $url == "short-link.ru/") {
	header('Location: http://short-link.ru/api/short/');
} else echo "Ошибка! Неизвестный запрос";

