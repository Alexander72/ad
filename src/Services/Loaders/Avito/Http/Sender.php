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
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Log\LoggerInterface;

class Sender implements SenderInterface
{
    use ParameterizableTrait;

    /**
     * Avito base url
     */
    const HOST = 'www.avito.ru';

    const PROTOCOL = 'https';

	/**
	 * @var LoggerInterface
	 */
    protected $logger;

    /**
     * @var RequestDelayer
     */
    protected $requestDelayer;

	/**
	 * @var Client
	 */
    protected $client;

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

		$stack = \GuzzleHttp\HandlerStack::create();
		$stack->push($this->getProfiler());
		$this->client = new Client([
			'base_uri' => self::PROTOCOL.'://'.self::HOST,
			'cookies' => true,
			'handler' => $stack,
			'allow_redirects' => false,
			//'debug' => true,
		]);
	}

	/**
	 * @param string $url
	 * @return string
	 * @throws \Exception
	 */
    public function send(string $url): string
    {
	    $this->requestDelayer->wait(self::class);

	    $this->logger->debug("Load data from url: ".self::HOST."$url");

	    $request = new Request('GET', $url, [
		    'Host' => self::HOST,
		    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:60.0) Gecko/20100101 Firefox/60.0',
		    'Connection' => 'close',
	    ]);

	    $response = $this->client->send($request);

	    if($response->getStatusCode() != 200)
	    {
		    $location = $response->getHeader('location')[0] ?? '< unable to get location >';
		    $redirect = in_array($response->getStatusCode(), [301, 302]) ? ' Are we blocked? Redirect url: ' . $location : '';
		    $message = 'Response from ' . self::PROTOCOL . '://' . self::HOST . $request->getUri() . ' returns with non 200 code: ' . $response->getStatusCode() . $redirect;
		    throw new \Exception($message);
	    }

	    $result = $response->getBody();

        return $result;
    }

	protected function getProfiler()
	{
		return function(callable $handler) {
			return function(\Psr\Http\Message\RequestInterface $request, array $options) use ($handler) {
				//echo 'Start request at '.microtime(1)."\n";
				return $handler($request, $options)->then(function(\Psr\Http\Message\ResponseInterface $response) {
					//echo 'End request at '.microtime(1)."\n";
					return $response;
				});
			};
		};
	}
}