<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:55
 */

namespace App\Services\Parsers\Avito;

use App\Services\Parsers\AbstractParser;

class FlatApiParser extends AbstractParser
{
    const SITE = 'avito';

	public function parse(string $flat): array
	{
	    $params = $this->getParams();

        $flats = json_decode($flat, 1);
        $flat = $this->getArrayValue($flats, 'items');
        $flat = reset($flat);

	    $result = [
	        'siteId'      => (integer) $this->getArrayValue($params, ['flat', 'id']),
	        'lat'         => (float) $this->getArrayValue($params, ['flat', 'lat']),
	        'lon'         => (float) $this->getArrayValue($params, ['flat', 'lon']),
	        'title'       => $this->getArrayValue($flat, 'title'),
            'url'         => $this->getArrayValue($flat, 'url'),
            'price'       => (integer) $this->getArrayValue($flat, 'price'),
            'type'        => $this->getArrayValue($flat, 'type'),
            'address'     => $this->getArrayValue($flat, ['ext', 'address']),
            'floor'       => (integer) $this->getArrayValue($flat, ['ext', 'floor']),
            'floorCount'  => (integer) $this->getArrayValue($flat, ['ext', 'floors_count']),
            'houseType'   => $this->getArrayValue($flat, ['ext', 'house_type']),
            'roomCount'   => (integer) $this->getArrayValue($flat, ['ext', 'rooms']),
            'area'        => (integer) $this->getArrayValue($flat, ['ext', 'area']),
            'areaKitchen' => (integer) $this->getArrayValue($flat, ['ext', 'area_kitchen']),
            'areaLive'    => (integer) $this->getArrayValue($flat, ['ext', 'area_live']),
            //'data'        => $flat,
        ];

	    $result['unitPrice'] = !empty($result['area']) ? $result['price'] / $result['area'] : 0;
	    $result['site'] = self::SITE;

        return $result;
	}

    protected function getArrayValue($array, $path)
    {
        $path = !is_array($path) ? [$path] : $path;

        foreach($path as $key)
        {
            if(!is_array($array) || !isset($array[$key]))
                return null;

            $array = $array[$key];
        }

        return $array;
	}
}