<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:55
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Item;

use App\Interfaces\Formatters\FormatterInterface;
use App\Services\Avito\Flat\Rent\Msk\Formatters\ItemFormatter;
use App\Services\Avito\Flat\Rent\Msk\Loaders\AbstractLoader;

class ItemLoader extends AbstractLoader
{
    const URL_PATH = '/js/catalog/items';

    protected $itemFormatter;

    public function __construct(
        ItemFormatter $itemFormatter
    ) {
        $this->itemFormatter = $itemFormatter;
    }

    protected function getUrl(array $params): string
    {
        //$url = "https://www.avito.ru/js/catalog/items?id=1044600709&lat=54.89726359110567&lng=38.32359993025392&priceDimensionValue=-1";

        $url = self::BASE_URL . self::URL_PATH;

        /** @TODO add parameter for long time rent ad only */
        /** @TODO params should build in other class; every param should be extracted to different class */
        $params = [
            'id' => $params['id'],
            'lat' => $params['lat'],
            'lng' => $params['lon'],
            'priceDimensionValue' => -1,
        ];

        return $url . '?' . http_build_query($params);
    }


    protected function getFormatter(): FormatterInterface
	{
	    return $this->itemFormatter;
	}

}