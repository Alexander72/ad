<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 19.06.18
 * Time: 1:02
 */

namespace App\Entity\Ads\Flats;

use App\Services\Loaders\Avito\FlatLoader;

class AdFlatFactory
{
	protected $loader;

	public function __construct(FlatLoader $loader)
	{
		$this->loader = $loader;
	}

	public function create(): Flat
	{
		$flat = new Flat();
		$flat->setLoader($this->loader);

		return $flat;
	}
}