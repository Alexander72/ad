<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 13:21
 */

namespace App\Services;


class AvitoItemService
{
	const REQUESTS_PER_SECOND = 5;

	const REQUEST_DURATION = 25;

	const BASE_URL = 'https://www.avito.ru/';

	const URL_PATH = 'js/catalog/items';

	protected $lastSendTime = 0;

	protected $proxy;

	public function __construct()
	{
	}


	public function get(array $item)
	{
		$url = $this->buildUrl($item);

		return $this->proxy->send($url);

	}

	protected function buildUrl(array $item)
	{
		$url = self::BASE_URL . self::URL_PATH;
		$params = [
			'id' => $item['id'],
			'lat' => $item['lat'],
			'lng' => $item['lng'],
			'priceDimensionValue' => -1,
		];

		return $url . '?' . http_build_query($params);
	}

	protected function canSend()
	{
		return microtime(1) - $this->lastSendTime > (1000 - self::REQUEST_DURATION * self::REQUESTS_PER_SECOND) / self::REQUESTS_PER_SECOND / 1000;
	}

}