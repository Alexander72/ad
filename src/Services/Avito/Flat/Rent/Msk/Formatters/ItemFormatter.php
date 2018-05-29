<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:55
 */

namespace App\Services\Avito\Flat\Rent\Msk\Formatters;

use App\Interfaces\Formatters\FormatterInterface;

class ItemFormatter implements FormatterInterface
{
	public function format(string $flat, $params = []): array
	{
        $flats = json_decode($flat, 1);
        $flat = $this->getArrayValue($flats, 'items');
        $flat = reset($flat);

	    $result = [
	        'id'          => $this->getArrayValue($params, ['item', 'id']),
	        'lan'         => $this->getArrayValue($params, ['item', 'lat']),
	        'lon'         => $this->getArrayValue($params, ['item', 'lon']),
	        'title'       => $this->getArrayValue($flat, 'title'),
            'url'         => $this->getArrayValue($flat, 'url'),
            'price'       => $this->getArrayValue($flat, 'price'),
            'type'        => $this->getArrayValue($flat, 'type'),
            'address'     => $this->getArrayValue($flat, ['ext', 'address']),
            'floor'       => $this->getArrayValue($flat, ['ext', 'floor']),
            'floorsTotal' => $this->getArrayValue($flat, ['ext', 'floors_count']),
            'houseType'   => $this->getArrayValue($flat, ['ext', 'house_type']),
            'rooms'       => $this->getArrayValue($flat, ['ext', 'rooms']),
            'area'        => $this->getArrayValue($flat, ['ext', 'area']),
            'areaKitchen' => $this->getArrayValue($flat, ['ext', 'area_kitchen']),
            'areaLive'    => $this->getArrayValue($flat, ['ext', 'area_live']),
            'data'        => $flat,
        ];
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