<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito\UrlParameters;

use Psr\Log\LoggerInterface;

class FlatsUrlGenerator
{
    const UP = 55.872297;
    const LEFT = 37.459958;
    const DOWN = 55.638917;
    const RIGHT = 37.825253;

    const WINDOW_SIZE = 0.018410973322936286;

    const SCALE = 14;

    const URL_PATH = '/js/catalog/coords';

    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getUrl()
    {
        /** @TODO params should build in other class; every param should be extracted to different class */
        $params = [
            'token[7590738133237]' => '6680263b5a4bc727',
            'category_id' => 24,//категория - квартиры
            'location_id' => 637640,//регион - москва
            'params[201]' => 1060,//тип объявления - сдам
            'params[504]' => 5256,//срок аренда - на длительный срок
        ];

        for($lat = self::DOWN; $lat <= self::UP; $lat += self::WINDOW_SIZE)
        {
            for($lon = self::LEFT; $lon <= self::RIGHT; $lon += self::WINDOW_SIZE)
            {
	            $params['geo'] = "$lat,$lon,".($lat + self::WINDOW_SIZE).",".($lon + self::WINDOW_SIZE).",".self::SCALE;

	            $percent = str_pad($this->getPercent($lat, $lon), 2, ' ', STR_PAD_LEFT);
	            $this->logger->info("Generate url for map window. $percent% percent passed. Window parameters: lat = $lat, lon = $lon.");

                yield self::URL_PATH . '?' . http_build_query($params);
            }
        }
    }

    private function getPercent($lat, $lon): int
    {
        $hCount = (self::UP - self::DOWN) / self::WINDOW_SIZE;
        $wCount = (self::RIGHT - self::LEFT) / self::WINDOW_SIZE;

        $currentHeightCount = floor(($lat - self::DOWN) / self::WINDOW_SIZE);
        $heightPercent = 1 / $hCount * $currentHeightCount;

        $currentWidthCount = floor(($lon - self::LEFT) / self::WINDOW_SIZE);
        $widthPercent = 1 / $wCount * $currentWidthCount;

        $percent = ($heightPercent + 1 / $hCount * $widthPercent) * 100;

        return $percent;
    }
}