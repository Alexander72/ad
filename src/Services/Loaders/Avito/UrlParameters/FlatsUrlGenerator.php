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

    const URL_PATH = '/js/catalog/coords';

    public function getUrl()
    {

        //$url = "https://www.avito.ru/js/catalog/coords?s=101&token[311201853452]=6f61873a1fa01d08&sgtd=&category_id=24&location_id=637640&name=&params[201]=1060&params[568][from]=&params[568][to]=&params[501][from]=&params[501][to]=&params[1460]=&params[502][from]=&params[502][to]=&pmin=&pmax=&params[504]=5256&geo=55.41457590373593,36.97339439257813,56.077633093174185,38.188756941406254,10,f";

        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.643480083814985,37.35467864648432,55.79076791804248,37.79687835351556,11";
        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.6803543426987,37.46522857324213,55.753998241662345,37.68632842675775,12";
        //$url = "https://www.avito.ru/js/catalog/coords?s_trg=3&s=101&token[8246784275542]=6f59afa0ea9e17cc&sgtd=&category_id=24&location_id=637640&name=&i=on&params[201]=&pmin=&pmax=&geo=55.70798714654133,37.54814101831049,55.726398119864264,37.603415981689395,14";
        //$url = "https://www.avito.ru/js/catalog/coords?token[7590738133237]=6680263b5a4bc727&category_id=24&location_id=637640&params[201]=1060&params[504]=5256&geo=55.72960108411808,37.585745785888676,55.76963576120057,37.66170594519042,14,f";


        $scale = 14;
	    $windowWidth = 0.018410973322936286;

        $url = self::URL_PATH;

        /** @TODO params should build in other class; every param should be extracted to different class */
        $params = [
            'token[7590738133237]' => '6680263b5a4bc727',
            'category_id' => 24,//категория - квартиры
            'location_id' => 637640,//регион - москва
            'params[201]' => 1060,//тип объявления - сдам
            'params[504]' => 5256,//срок аренда - на длительный срок
        ];

        for($lat = self::DOWN; $lat <= self::UP; $lat += $windowWidth)
        {
            for($lon = self::LEFT; $lon <= self::RIGHT; $lon += $windowWidth)
            {
	            $params['geo'] = "$lat,$lon,".($lat+ $windowWidth).",".($lon+$windowWidth).",$scale";
                yield $url . '?' . http_build_query($params);
            }
        }
    }
}