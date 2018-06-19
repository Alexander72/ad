<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito\Http;

use App\Interfaces\Loaders\SenderInterface;
use App\Traits\ParameterizableTrait;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{
    use ParameterizableTrait;

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
	 * @var LoggerInterface
	 */
    protected $logger;

    /**
     * @var float
     */
    static protected $lastSendTime = 0;

    /**
     * Sender constructor.
     *
     * @param LoggerInterface $logger
     */
	public function __construct(
		LoggerInterface $logger
	) {
		$this->logger = $logger;
	}

	/**
     * @param $url
     * @return string
     */
    public function send(string $url): string
    {
        $url = self::BASE_URL . $url;

	    $this->waitBeforeSend();

	    $this->logger->debug("Load data from url: $url");

	    $profilerStartTime = microtime(1);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($ch);

	    $requestDuration = (microtime(1) - $profilerStartTime) * 1000;
	    $this->logger->debug("Request takes $requestDuration milliseconds");

        self::$lastSendTime = microtime(1);

        return $result;
    }

    private function waitBeforeSend(): self
    {
    	/**
	     * @TODO Move waiting to separate independent static class.
	     * Every loader uses own instance of sender that is why sender will always wait before the next request.
	     */
        $rps = self::NON_BLOCKED_REQUESTS_PER_SECOND;

        // The delay in milliseconds that must be waited between requests as
        // if the requests are sent in a row without delay for processing
        $delayInMilliseconds = (1000 - $rps * self::REQUEST_DURATION) / $rps;

        // delay correction because of request processing
        $delayInMilliseconds = max(0, $delayInMilliseconds - (microtime(1) - self::$lastSendTime) * 1000);
        $this->logger->debug("Wait $delayInMilliseconds milliseconds before send query to ".self::BASE_URL);
        usleep(1000 * $delayInMilliseconds);

        return $this;
    }
}