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
	public function format(string $flats): array
	{
		/** @TODO refactor */
		return json_decode($flats, 1);
	}
}