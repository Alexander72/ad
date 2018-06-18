<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Parsers\Avito;
require_once($_SERVER['DOCUMENT_ROOT'] . 'vendor/electrolinux/phpquery/phpQuery/phpQuery.php');

use App\Interfaces\Parsers\ParserInterface;

class FlatParser implements ParserInterface
{
    protected $pq = 'pq';

    public function parse(string $input): array
    {
        \phpQuery::newDocument($input);

        $mapElement = pq('.item-view-map .b-search-map expanded');
        $titleElement = pq('.item-view-page-layout .title-info-title-text');
        $descriptionElement = pq('.item-view-page-layout .item-description-text');
        $published = pq('.item-view-page-layout .title-info .title-info-metadata .title-info-metadata-item')->first()->html();

        return [
            'id' => $mapElement->data('item-id'),
            'lat' => $mapElement->data('map-lat'),
            'lon' => $mapElement->data('map-lon'),
            'title' => $titleElement->text(),
            'description' => $descriptionElement->html(),
            'published' => $published,
        ];
    }

    protected function formatPublished($published)
    {
        // № 826273989, размещено сегодня в 11:52
        // № 149781707, размещено 10 июня в 11:41

        trim($published);
        preg_match('/^№ \d+, размещено (.*)$/', $published, $matches);

    }
}
