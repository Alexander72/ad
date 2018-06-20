<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:55
 */

namespace App\Services\Loaders\Avito;

use App\Interfaces\Formatters\FormatterInterface;
use App\Interfaces\Loaders\SenderInterface;
use App\Interfaces\Parsers\ParserInterface;
use App\Services\Loaders\Avito\Http\Sender;
use App\Services\Parsers\Avito\FlatApiParser;
use App\Services\Loaders\AbstractLoader;

class FlatLoader extends AbstractLoader
{
    const API_URL_PATH = '/js/catalog/items';

    /**
     * @var FlatApiParser
     */
    protected $apiParser;

    public function __construct(
        SenderInterface $sender,
        ParserInterface $parser,
        FlatApiParser $apiParser
    ) {
        parent::__construct($sender, $parser);
        $this->apiParser = $apiParser;
    }


    public function load(): array
    {
        // load data via API
        $url = $this->getApiUrl();
        $response = $this->sender->send($url);

        $this->apiParser->setParams($this->getParams());
        $apiData = $this->apiParser->parse($response);

        // load data using html parsing
        $this->setParams(['flat' => $apiData]);

        $url = $this->getUrl();
        $response = $this->sender->send($url);
        $data = $this->parser->parse($response);

        return array_merge($apiData, $data);
    }

    protected function getApiUrl(): string
    {
        /** @TODO make check  if flat does not exists in params */
        $flat = $this->getParam('flat');

        $url = self::API_URL_PATH;

        /** @TODO params should build in other class; every param should be extracted to different class */
        $params = [
            'id' => $flat['id'],
            'lat' => $flat['lat'],
            'lng' => $flat['lon'],
            'priceDimensionValue' => -1,
        ];

        return $url . '?' . http_build_query($params);
    }

    protected function getUrl(): string
    {
        $flat = $this->getParam('flat');
        return $flat['url'];
    }
}
