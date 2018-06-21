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

	    $options = [
            CURLOPT_RETURNTRANSFER => true,   // return web page
            CURLOPT_HTTPHEADER => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36',
            //CURLOPT_HEADER         => true,  // don't return headers
            //CURLOPT_USERAGENT      => "test", // name of client
            //CURLOPT_CONNECTTIMEOUT => 5,    // time-out on connect
            //CURLOPT_TIMEOUT        => 5,    // time-out on response
        ];

	    /** @TODO use cookie to prevent blocking */

        $ch = curl_init($url);
	    curl_setopt_array($ch, $options);
	    $result = curl_exec($ch);
	    $info = curl_getinfo($ch);
	    if($info['http_code'] != 200)
        {
            $redirectMessage = $info['http_code'] == 302 ? 'Redirect to '.$info['redirect_url'].'. Are we blocked?' : '';
            $message = 'Server reply with non-200 status: '.$info['http_code'].'.'.$redirectMessage.' Trying to get response from: '.$url;
            throw new \Exception($message);
        }
	    $error = curl_error($ch);

	    curl_close($ch);

	    $requestDuration = (microtime(1) - $profilerStartTime) * 1000;
	    $this->logger->debug("Request takes $requestDuration milliseconds");

        return $result;
    }
}