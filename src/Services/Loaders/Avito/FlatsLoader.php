<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito;

use App\Interfaces\Loaders\SenderInterface;
use App\Interfaces\Parsers\ParserInterface;
use App\Services\Loaders\AbstractLoader;
use App\Services\Loaders\Avito\UrlParameters\FlatsUrlGenerator;

class FlatsLoader extends AbstractLoader
{
    /**
     * @var FlatsUrlGenerator
     */
    protected $urlGenerator;

    /**
     * FlatsLoader constructor.
     * @param SenderInterface $sender
     * @param ParserInterface $parser
     * @param FlatsUrlGenerator $urlGenerator
     */
    public function __construct(
        SenderInterface $sender,
        ParserInterface $parser,
        FlatsUrlGenerator $urlGenerator
    ) {
        parent::__construct($sender, $parser);

        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @return \Generator
     * @throws \App\Exceptions\ParseException
     */
    public function load(): \Generator
    {
        foreach($this->urlGenerator->getUrl() as $url)
        {
            $response = $this->sender->send($url);
            $this->parser->setParams($this->getParams());
            yield $this->parser->parse($response);
        }
    }
}
