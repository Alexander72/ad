<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito;

use App\Exceptions\ParseException;
use App\Interfaces\Loaders\SenderInterface;
use App\Interfaces\Parsers\ParserInterface;
use App\Services\Loaders\AbstractLoader;
use App\Services\Loaders\Avito\UrlParameters\FlatsUrlGenerator;
use Psr\Log\LoggerInterface;

class FlatsLoader extends AbstractLoader
{
    /**
     * @var FlatsUrlGenerator
     */
    protected $urlGenerator;

	/**
	 * @var LoggerInterface
	 */
    protected $logger;

    /**
     * FlatsLoader constructor.
     * @param SenderInterface $sender
     * @param ParserInterface $parser
     * @param FlatsUrlGenerator $urlGenerator
     */
    public function __construct(
        SenderInterface $sender,
        ParserInterface $parser,
        FlatsUrlGenerator $urlGenerator,
		LoggerInterface $logger
    ) {
        parent::__construct($sender, $parser);

        $this->urlGenerator = $urlGenerator;
        $this->logger = $logger;
    }

    /**
     * @return \Generator
     */
    public function load(): \Generator
    {
        foreach($this->urlGenerator->getUrl() as $url)
        {
        	try
	        {
		        $response = $this->sender->send($url);
	        }
	        catch(\Exception $exception)
	        {
	        	$this->logger->warning('Failed to load flats list: '.$exception->getMessage());
	        	continue;
	        }

            $this->parser->setParams($this->getParams());
            try
            {
                $flats = $this->parser->parse($response);
            }
            catch(ParseException $exception)
            {
                $flats = [];
            }

            yield $flats;
        }
    }
}
