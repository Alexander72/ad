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

        $mapElement = pq('.item-view-map .b-search-map');
        $descriptionElement = pq('.item-view-page-layout .item-description-text');
	    $publishedText = pq('.item-view-page-layout .title-info .title-info-metadata .title-info-metadata-item')->contents()->eq(0)->text();
        $published = $this->formatPublished($publishedText);

        return [
            'id' => (int) $mapElement->attr('data-item-id'),
            'lat' => (float) $mapElement->attr('data-map-lat'),
            'lon' => (float) $mapElement->attr('data-map-lon'),
            'description' => trim($descriptionElement->text()),
            'published' => $published,
        ];
    }

    /**
     * @param $published
     * @return \DateTime
     * @throws \App\Exceptions\DateParseException
     */
    protected function formatPublished($published): \DateTime
    {
	    $published = trim($published);
        preg_match('/^№ \d+, размещено (.*)$/', $published, $matches);
        /** @TODO process undefined index 1 */
        return $this->dateParser->parse($matches[1]);
    }
}
