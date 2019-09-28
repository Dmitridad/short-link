<?php

require_once('./database/db.php');

Class ApiMethods {

	public function makeShort($userIp, $longLink)
	{	
		$dbObj = new Database();

		$symbolSet = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

		do {
			$randStr = substr(str_shuffle($symbolSet), 0, 5);
			$shortLink = 'http://short-link.ru/' . $randStr;
			$selectResult = $dbObj->makeSelect("SELECT short_link FROM link_data WHERE short_link = '$shortLink'");
		} while ($selectResult == false);
		
		$insertResult = $dbObj->makeInsert("INSERT INTO link_data (long_link, short_link, user_ip, number_of_calls) 
			VALUES ('$longLink', '$shortLink', '$userIp', 0)");

		echo "Ваша сокращенная ссылка: " . "<a href=\"$shortLink\">$shortLink</a>";
	}

	public function redirect($shortLink)
	{
		$dbObj = new Database();

		$selectResult = $dbObj->makeSelect("SELECT long_link FROM link_data WHERE short_link = '$shortLink'");

		foreach ($selectResult as $value) {
			if (!empty($value['long_link'])) {
				$longLink = $value['long_link'];
			}
		}

		if (!$longLink) {
			echo "Данной ссылки не существует!";
		} else {
			header("Location: $longLink");
			$updateResult = $dbObj->makeInsert("UPDATE link_data SET number_of_calls = number_of_calls + 1 WHERE short_link = '$shortLink'");
		}
	}

	public function showLinks($userIp)
	{
		$dbObj = new Database();

		$selectResult = $dbObj->makeSelect("SELECT long_link FROM link_data WHERE user_ip = '$userIp'");
		$selectResult = $selectResult->fetchAll();

		if (empty($selectResult)) {
			echo "Список ваших ссылок пуст";
		} else {
			$i = 1;
			echo "Ваш список сокращенных ссылок: " . "</br>" . "</br>";
			foreach ($selectResult as $key => $value) {
				$longLink = $value['long_link'];
				echo "$i. " . "<a href=\"$longLink\">$longLink</a>" . "</br>";
				$i++;
			}
		}
	}

	public function showStatistics($userIp)
	{
		$dbObj = new Database();

		$selectResult = $dbObj->makeSelect("SELECT short_link, long_link, number_of_calls FROM link_data WHERE user_ip = '$userIp'");
		$selectResult = $selectResult->fetchAll();

		if (empty($selectResult)) {
			echo "Мы не можем предоставить вам статистику, так как список ваших ссылок пуст";
		} else {
			$i = 1;
			echo "Ваша статистика переходов по ссылкам: " . "</br>" . "</br>";

			foreach ($selectResult as $key => $value) {
				$shortLink = $value['short_link'];
				$longLink = $value['long_link'];
				$calls = $value['number_of_calls'];
				echo "$i. " . "<a href=\"$shortLink\">$shortLink</a>" . " ($longLink) " . "-" . " $calls" . "</br>";
				$i++;
			}
		}
	}
}