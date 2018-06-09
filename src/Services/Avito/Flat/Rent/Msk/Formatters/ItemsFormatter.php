<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:49
 */

namespace App\Services\Avito\Flat\Rent\Msk\Formatters;

use App\Interfaces\Formatters\FormatterInterface;

class ItemsFormatter implements FormatterInterface
{
    /**
     * @param string $flats
     * @param array $params
     * @return array
     * @throws \Exception
     */
	public function format(string $flats, $params = []): array
	{
		$flats = json_decode($flats, 1);
		if(!isset($flats['coords']))
        {
            /** @TODO create specific exception type */
            throw new \Exception("Avito api format has changed. The 'coords' key does not exists. Presented keys: ".implode(', ', array_keys($flats)));
        }

        $flats = $flats['coords'];

        $formattedFlats = [];
        foreach($flats as $flatId => $flat)
        {
            $formattedFlats[$flatId] = [
                'id' => $flatId,
                'lat' => $flat['lat'],
                'lon' => $flat['lon'],
            ];
        }

		return $formattedFlats;
	}
}