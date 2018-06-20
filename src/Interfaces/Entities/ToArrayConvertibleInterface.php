<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.06.18
 * Time: 0:43
 */

namespace App\Interfaces\Entities;


interface ToArrayConvertibleInterface
{
	/**
	 * Convert entity data that stored in DB to array
	 *
	 * @return array
	 */
	public function toArray(): array;
}