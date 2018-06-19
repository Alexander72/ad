<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:49
 */

namespace App\Services\Parsers\Avito;

use App\Exceptions\ParseException;
use App\Services\Parsers\AbstractParser;

class FlatsApiParser extends AbstractParser
{
    /**
     * @param string $flats
     * @return array
     * @throws \Exception
     */
	public function parse(string $flats): array
	{
		$flats = json_decode($flats, 1);
		if(!isset($flats['coords']))
        {
            throw new ParseException("Avito api format has changed. The 'coords' key does not exists. Presented keys: ".implode(', ', array_keys($flats)));
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