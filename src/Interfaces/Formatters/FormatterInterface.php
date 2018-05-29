<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 17:26
 */

namespace App\Interfaces\Formatters;


interface FormatterInterface
{
	/**
	 * Formats input string to array
	 * @param string $input
	 * @return array
	 */
	public function format(string $input, $params = []): array;
}