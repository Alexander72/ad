<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:51
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Items\UrlParameters;


abstract class AbstractParameter
{
	abstract public function getName(): string;
	abstract public function getValue(): string;
}