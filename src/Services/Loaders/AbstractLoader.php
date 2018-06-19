<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:21
 */

namespace App\Services\Loaders;

use App\Exceptions\LoadException;
use App\Exceptions\ParseException;
use App\Interfaces\Loaders\LoaderInterface;
use App\Interfaces\Loaders\SenderInterface;
use App\Interfaces\Parsers\ParserInterface;
use App\Traits\ParameterizableTrait;

abstract class AbstractLoader implements LoaderInterface
{
    use ParameterizableTrait;

    /**
     * @var SenderInterface
     */
	protected $sender;

    /**
     * @var ParserInterface
     */
	protected $parser;

    /**
     * AbstractLoader constructor.
     *
     * @param SenderInterface $sender
     * @param ParserInterface $parser
     */
    public function __construct(
        SenderInterface $sender,
        ParserInterface $parser
    ) {
        $this->sender = $sender;
        $this->parser = $parser;
    }

    /**
     * @return mixed
     * @throws LoadException
     */
    public function load()
	{
		$url = $this->getUrl();
		$response = $this->sender->send($url);

		try
        {
            $this->parser->setParams($this->getParams());
            $result = $this->parser->parse($response);
        }
        catch (ParseException $exception)
        {
            throw new LoadException('Failed to load item due to parse error: ' . $exception->getMessage());
        }

        return $result;
	}

    /**
     * @return string
     * @throws \Exception
     */
	protected function getUrl(): string
    {
        throw new \Exception("Cannot call ".__METHOD__." directly.");
    }
}
