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
use App\Services\Parsers\AbstractParser;
use App\Services\Parsers\DateParser;

class FlatHtmlParser extends AbstractParser
{
    protected $dateParser;

	public function __construct(
		DateParser $dateParser
	) {
		$this->dateParser = $dateParser;
	}

	public function parse(string $input): array
    {
        \phpQuery::newDocument($input);

        $mapElement = pq('.item-view-map .b-search-map expanded');
        $descriptionElement = pq('.item-view-page-layout .item-description-text');
        $publishedText = pq('.item-view-page-layout .title-info .title-info-metadata .title-info-metadata-item')->first()->html();
        $published = $this->formatPublished($publishedText);

        return [
            'id' => $mapElement->data('item-id'),
            'lat' => $mapElement->data('map-lat'),
            'lon' => $mapElement->data('map-lon'),
            'description' => $descriptionElement->html(),
            'published' => $published->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @param $published
     * @return \DateTime
     * @throws \App\Exceptions\DateParseException
     */
    protected function formatPublished($published): \DateTime
    {
        trim($published);
        preg_match('/^№ \d+, размещено (.*)$/', $published, $matches);
        return $this->dateParser->parse($matches[1]);
    }
}
