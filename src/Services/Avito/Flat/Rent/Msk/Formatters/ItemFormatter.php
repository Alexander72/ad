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
	public function format(string $flat): array
	{
        /** @TODO refactor */
        return json_decode($flat, 1);
	}
}