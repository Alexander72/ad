<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 13:12
 */

namespace App\Entity;


class Ad
{
	protected $id;
	protected $type;
	protected $site;
	protected $region;
	protected $data;

	/**
	 * @return mixed
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param mixed $id
	 * @return Ad
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * @param mixed $type
	 * @return Ad
	 */
	public function setType($type)
	{
		$this->type = $type;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getSite()
	{
		return $this->site;
	}

	/**
	 * @param mixed $site
	 * @return Ad
	 */
	public function setSite($site)
	{
		$this->site = $site;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getRegion()
	{
		return $this->region;
	}

	/**
	 * @param mixed $region
	 * @return Ad
	 */
	public function setRegion($region)
	{
		$this->region = $region;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param mixed $data
	 * @return Ad
	 */
	public function setData($data)
	{
		$this->data = $data;

		return $this;
	}

}