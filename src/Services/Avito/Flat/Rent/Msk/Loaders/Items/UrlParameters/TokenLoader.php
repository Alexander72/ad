<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:45
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Items\UrlParameters;


class TokenLoader
{
	public function getToken(): Token
	{
		return new Token();
	}
}