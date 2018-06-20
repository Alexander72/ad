<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.06.18
 * Time: 0:47
 */

namespace App\Interfaces\Entities;


interface FromArrayConvertibleInterface
{
	/**
	 * Load data to entity
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function fromArray(array $data);
}