<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:49
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Items;


use App\Interfaces\Formatters\FormatterInterface;
use App\Services\Avito\Flat\Rent\Msk\Formatters\ItemsFormatter;
use App\Services\Avito\Flat\Rent\Msk\Loaders\AbstractLoader;
use App\Services\Avito\Flat\Rent\Msk\Loaders\Items\UrlParameters\TokenLoader;

class ItemsLoader extends AbstractLoader
{
	const URL_PATH = '/js/catalog/coords';

	protected $itemsFormatter;

	public function __construct(
		ItemsFormatter $itemsFormatter
	) {
		$this->itemsFormatter = $itemsFormatter;
	}

	protected function getUrl(array $params): string
	{
		//$url = "https://www.avito.ru/js/catalog/coords?s=101&token[311201853452]=6f61873a1fa01d08&sgtd=&category_id=24&location_id=637640&name=&params[201]=1060&params[568][from]=&params[568][to]=&params[501][from]=&params[501][to]=&params[1460]=&params[502][from]=&params[502][to]=&pmin=&pmax=&params[504]=5256&geo=55.41457590373593,36.97339439257813,56.077633093174185,38.188756941406254,10,f";

		$url = self::BASE_URL . self::URL_PATH;

		/** @TODO params should build in other class; every param should be extracted to different class */
		$params = [
			's' => 101,
			'token[311201853452]' => '6f61873a1fa01d08',
			'category_id' => 24,
			'location_id' => '637640',
			'geo' => '55.41457590373593,36.97339439257813,56.077633093174185,38.188756941406254,10,f',
		];

		return $url . '?' . http_build_query($params);
	}

	protected function getFormatter(): FormatterInterface
	{
		return $this->itemsFormatter;
	}


	protected function getUrlPath(): string
	{
		return self::URL_PATH;
	}
}