<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:48
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Items\UrlParameters;


class CategoryId
{
	protected $categoryId = 24;

	/**
	 * @return int
	 */
	public function getCategoryId(): int
	{
		return $this->categoryId;
	}

	/**
	 * @param int $categoryId
	 * @return CategoryId
	 */
	public function setCategoryId(int $categoryId): CategoryId
	{
		$this->categoryId = $categoryId;

		return $this;
	}


}