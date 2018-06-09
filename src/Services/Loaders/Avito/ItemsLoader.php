<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito;


use App\Services\Avito\Flat\Rent\Msk\Formatters\ItemsFormatter;
use App\Services\Loaders\Avito\Http\Sender;
use App\Services\Loaders\Avito\UrlParameters\FlatsUrlGenerator;

class ItemsLoader
{
    /**
     * @var Sender
     */
    private $sender;

    /**
     * @var FlatsUrlGenerator
     */
    private $urlGenerator;

    /**
     * @var ItemsFormatter
     */
    private $itemsFormatter;

    /**
     * ItemsLoader constructor.
     * @param Sender $sender
     * @param FlatsUrlGenerator $urlGenerator
     * @param ItemsFormatter $itemsFormatter
     */
    public function __construct(
        Sender $sender,
        FlatsUrlGenerator $urlGenerator,
        ItemsFormatter $itemsFormatter
    ) {
        $this->sender = $sender;
        $this->urlGenerator = $urlGenerator;
        $this->itemsFormatter = $itemsFormatter;
    }

    /**
     * @return \Generator
     * @throws \Exception
     */
    public function load(): \Generator
    {
        foreach($this->urlGenerator->getUrl() as $url)
        {
            $response = $this->sender->send($url);
            yield $this->itemsFormatter->format($response);
        }
    }
}
