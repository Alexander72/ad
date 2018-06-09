<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:21
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders;


use App\Interfaces\Formatters\FormatterInterface;

abstract class AbstractLoader
{
	/**
	 * Request per second count allowed to send without ip blocking by Avito
	 */
	const NON_BLOCKED_REQUESTS_PER_SECOND = 5;

	/**
	 * Request duration in ms
	 */
	const REQUEST_DURATION = 25;

	/**
	 * Avito base url
	 */
	const BASE_URL = 'https://www.avito.ru';

	/**
	 * @var float
	 */
	static protected $lastSendTime = 0;

	public function load(array $params = []): array
	{
		$url = $this->getUrl($params);
		$response = $this->send($url);
		return $this->getFormatter()->format($response, ['item' => $params]);
	}

	abstract protected function getUrl(array $params): string;
	abstract protected function getFormatter(): FormatterInterface;

	private function send($url): string
	{
		$this->waitBeforeSend();

		$result = file_get_contents($url);

		self::$lastSendTime = microtime(1);

		return $result;
	}

	private function waitBeforeSend(): self
	{
		$rps = self::NON_BLOCKED_REQUESTS_PER_SECOND;

		// The delay in milliseconds that must be waited between requests as
		// if the requests are sent in a row without delay for processing
		$delayInMilliseconds = (1000 - $rps * self::REQUEST_DURATION) / $rps;

		// delay correction because of request processing
		$delayInMilliseconds = max(0, $delayInMilliseconds - (microtime(1) - self::$lastSendTime) * 1000);
		usleep(1000 * $delayInMilliseconds);

		return $this;
	}
}