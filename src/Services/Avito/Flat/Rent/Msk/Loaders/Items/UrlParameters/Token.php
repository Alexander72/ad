<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 16:39
 */

namespace App\Services\Avito\Flat\Rent\Msk\Loaders\Items\UrlParameters;


class Token
{
	protected $name = '311201853452';
	protected $value = '6f61873a1fa01d08';

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Token
	 */
	public function setName(string $name): Token
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue(): string
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return Token
	 */
	public function setValue(string $value): Token
	{
		$this->value = $value;

		return $this;
	}

}