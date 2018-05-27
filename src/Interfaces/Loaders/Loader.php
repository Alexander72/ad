<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:50
 */

namespace App\Interfaces\Loaders;


interface Loader
{
	/**
	 * Load items from site
	 * @return string
	 */
	public function load(): string;
}