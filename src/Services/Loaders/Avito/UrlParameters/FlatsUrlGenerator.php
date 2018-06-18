<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito\UrlParameters;


class FlatsUrlGenerator
{
    const UP = 55.872297;
    const LEFT = 37.459958;
    const DOWN = 55.638917;
    const RIGHT = 37.825253;

    const WINDOW_SIZE = 0.018410973322936286;

    const SCALE = 14;

    const URL_PATH = '/js/catalog/coords';

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
                yield self::URL_PATH . '?' . http_build_query($params);
            }
        }
    }
}