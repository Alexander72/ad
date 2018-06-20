<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito\Http;

use App\Interfaces\Loaders\SenderInterface;
use App\Services\Loaders\RequestDelayer;
use App\Traits\ParameterizableTrait;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{
    use ParameterizableTrait;

    /**
     * Avito base url
     */
    const BASE_URL = 'https://www.avito.ru';

	/**
	 * @var LoggerInterface
	 */
    protected $logger;

    /**
     * @var RequestDelayer
     */
    protected $requestDelayer;

    /**
     * Sender constructor.
     *
     * @param LoggerInterface $logger
     */
	public function __construct(
		LoggerInterface $logger
	) {
		$this->logger = $logger;
		$this->requestDelayer = RequestDelayer::getDelayer();
	}

	/**
     * @param $url
     * @return string
     */
    public function send(string $url): string
    {
        $url = self::BASE_URL . $url;

	    $this->requestDelayer->wait(self::class);

	    $this->logger->debug("Load data from url: $url");

	    $profilerStartTime = microtime(1);

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $result = curl_exec($ch);

	    $requestDuration = (microtime(1) - $profilerStartTime) * 1000;
	    $this->logger->debug("Request takes $requestDuration milliseconds");

        return $result;
    }
}