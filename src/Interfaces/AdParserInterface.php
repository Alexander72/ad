<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 13:18
 */

namespace App\Interfaces;


interface AdParserInterface
{
	/**
	 * Parse input ad string in html\json\etc to array
	 * @param $string
	 * @return array
	 */
	public function parse($string): array;
}