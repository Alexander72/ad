<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 21.06.18
 * Time: 0:50
 */

namespace App\Traits\Entities;

trait ArrayImportedExportedTrait
{
	/**
	 * Load data to entity
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function fromArray(array $data): self
	{
		foreach($data as $field => $value)
		{
			if($this->isSystemField($field))
			{
				continue;
			}

			$method = 'set' . ucfirst($field);
			if(method_exists($this, $method))
			{
				$this->$method($value);
			}
		}

		return $this;
	}

	/**
	 * Convert entity data that stored in DB to array
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		$result = [];

		foreach($this as $field => $value)
		{
			if($this->isSystemField($field))
			{
				continue;
			}

			$method = 'get' . ucfirst($field);
			if(method_exists($this, $method))
			{
				$result[$field] = $this->$method();
			}
		}

		return $result;
	}

	/**
	 * Return true for fields that should not be included to array in toArray method
	 *
	 * @param string $field
	 * @return bool
	 */
	protected function isSystemField(string $field)
	{
		return in_array($field, $this->getSystemFields());
	}

	/**
	 * Return array of all system fields
	 *
	 * @return array
	 */
	protected function getSystemFields(): array
	{
		return [];
	}
}