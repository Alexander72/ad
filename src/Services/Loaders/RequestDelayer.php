<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 20.06.18
 * Time: 1:31
 */

namespace App\Services\Loaders;


use App\Services\Loaders\Avito\Http\Sender as AvitoSender;

class RequestDelayer
{
	static protected $delayer;

	const SITE_SENDER_CONFIGS = [
		AvitoSender::class => [
			'nonBlockedRPS' => 5,
			'requestDuration' => 25,
		],
	];

	protected $lastRequestEndTimes = [];

	protected function __construct()
	{
	}

	/**
	 * Set timestamp with milliseconds of the last request sent by specific sender
	 *
	 * @param string $senderClass
	 * @param float $timestamp
	 * @return RequestDelayer
	 */
	public function setLastRequestEndTime(string $senderClass, float $timestamp): self
	{
		$this->lastRequestEndTimes[$senderClass] = $timestamp;

		return $this;
	}

	/**
	 * Wait a little before next request will be sent by specific sender
	 *
	 * @param string $senderClass
	 * @return $this
	 */
	public function wait(string $senderClass)
	{
		$rps = self::SITE_SENDER_CONFIGS[$senderClass]['nonBlockedRPS'];

		// The delay in milliseconds that must be waited between requests as
		// if the requests are sent in a row without delay for processing
		$delayInMilliseconds = (1000 - $rps * self::SITE_SENDER_CONFIGS[$senderClass]['requestDuration']) / $rps;

		// delay correction because of request processing
		$delayInMilliseconds = max(0, $delayInMilliseconds - (microtime(1) - $this->lastRequestEndTimes[$senderClass]['lastRequestEndTime']) * 1000);
		usleep(1000 * $delayInMilliseconds);

		return $this;
	}

	/**
	 * Get RequestDelayer singleton
	 *
	 * @return RequestDelayer
	 */
	static public function getDelayer(): self
	{
		if(!self::$delayer)
		{
			self::$delayer = new static();
		}

		return self::$delayer;
	}
}
