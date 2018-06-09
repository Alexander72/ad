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
    const UP = 56.088014;
    const LEFT = 37.101050;
    const DOWN = 55.347819;
    const RIGHT = 38.325133;

    const URL_PATH = '/js/catalog/coords';

    public function getUrl()
    {

        //$url = "https://www.avito.ru/js/catalog/coords?s=101&token[311201853452]=6f61873a1fa01d08&sgtd=&category_id=24&location_id=637640&name=&params[201]=1060&params[568][from]=&params[568][to]=&params[501][from]=&params[501][to]=&params[1460]=&params[502][from]=&params[502][to]=&pmin=&pmax=&params[504]=5256&geo=55.41457590373593,36.97339439257813,56.077633093174185,38.188756941406254,10,f";

        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.643480083814985,37.35467864648432,55.79076791804248,37.79687835351556,11";
        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.6803543426987,37.46522857324213,55.753998241662345,37.68632842675775,12";
        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.70798714654133,37.54814101831049,55.726398119864264,37.603415981689395,14";


        $scale = 14;

        $url = self::URL_PATH;

        /** @TODO params should build in other class; every param should be extracted to different class */
        $params = [
            's' => 101,
            'token[311201853452]' => '6f61873a1fa01d08',
            'category_id' => 24,
            'location_id' => '637640',
            //'geo' => '55.41457590373593,36.97339439257813,56.077633093174185,38.188756941406254,10,f',
            //'geo' => '55.70798714654133,37.54814101831049,55.726398119864264,37.603415981689395,14',
        ];

        for($lat = self::DOWN; $lat <= self::UP; $lat += (self::UP - self::DOWN) / 15)
        {
            for($lon = self::LEFT; $lon <= self::RIGHT; $lon += (self::RIGHT - self::LEFT) / 15)
            {
                $params['geo'] = "$lat,$lon,".($lat+0.018410973322936286).",".($lon+0.018410973322936286).",$scale";
                yield $url . '?' . http_build_query($params);
            }
        }
    }
}