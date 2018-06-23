<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:56
 */

namespace App\Entity\Ads;

use App\Exceptions\CannotGetFieldValueForEsException;
use App\Exceptions\LoadException;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Flat
 * @ORM\Entity
 * @ORM\Table(name="flat")
 * @ORM\Table(
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="site_id_unique", columns={"site", "site_id"})
 *    }
 * )
 */
class Flat extends AbstractAd
{
	const ES_TYPE = "flat";

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $roomCount;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $floor;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $floorCount;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $address;

	/**
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	protected $houseType;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $area;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $areaKitchen;

	/**
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $areaLive;

	/**
	 * @ORM\Column(type="float")
	 */
	protected $lat;

	/**
	 * @ORM\Column(type="float")
	 */
	protected $lon;

	/**
	 * @ORM\Column(type="integer")
	 */
	protected $unitPrice;

	/**
	 * @return mixed
	 */
	public function getRoomCount()
	{
		return $this->roomCount;
	}

	/**
	 * @param mixed $roomCount
	 * @return AbstractFlatAd
	 */
	public function setRoomCount($roomCount)
	{
		$this->roomCount = $roomCount;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFloor()
	{
		return $this->floor;
	}

	/**
	 * @param mixed $floor
	 * @return AbstractFlatAd
	 */
	public function setFloor($floor)
	{
		$this->floor = $floor;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getFloorCount()
	{
		return $this->floorCount;
	}

	/**
	 * @param mixed $floorCount
	 * @return AbstractFlatAd
	 */
	public function setFloorCount($floorCount)
	{
		$this->floorCount = $floorCount;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAddress()
	{
		return $this->address;
	}

	/**
	 * @param mixed $address
	 * @return AbstractFlatAd
	 */
	public function setAddress($address)
	{
		$this->address = $address;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getHouseType()
	{
		return $this->houseType;
	}

	/**
	 * @param mixed $houseType
	 * @return AbstractFlatAd
	 */
	public function setHouseType($houseType)
	{
		$this->houseType = $houseType;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getArea()
	{
		return $this->area;
	}

	/**
	 * @param mixed $area
	 * @return AbstractFlatAd
	 */
	public function setArea($area)
	{
		$this->area = $area;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAreaKitchen()
	{
		return $this->areaKitchen;
	}

	/**
	 * @param mixed $areaKitchen
	 * @return AbstractFlatAd
	 */
	public function setAreaKitchen($areaKitchen)
	{
		$this->areaKitchen = $areaKitchen;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getAreaLive()
	{
		return $this->areaLive;
	}

	/**
	 * @param mixed $areaLive
	 * @return AbstractFlatAd
	 */
	public function setAreaLive($areaLive)
	{
		$this->areaLive = $areaLive;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLat()
	{
		return $this->lat;
	}

	/**
	 * @param mixed $lat
	 * @return AbstractFlatAd
	 */
	public function setLat($lat)
	{
		$this->lat = $lat;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getLon()
	{
		return $this->lon;
	}

	/**
	 * @param mixed $lon
	 * @return AbstractFlatAd
	 */
	public function setLon($lon)
	{
		$this->lon = $lon;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUnitPrice()
	{
		if(is_null($this->unitPrice))
		{
			$this->unitPrice = $this->getArea() ? $this->getPrice() / $this->getArea() : 0;
		}

		return $this->unitPrice;
	}

	/**
	 * @param mixed $unitPrice
	 * @return AbstractFlatAd
	 */
	protected function setUnitPrice($unitPrice)
	{
		$this->unitPrice = $unitPrice;
		return $this;
	}

	/**
	 * @return string
	 */
	public static function getEsType()
	{
		return self::ES_TYPE;
	}

	/**
	 * @param $field
	 * @return mixed
	 * @throws CannotGetFieldValueForEsException
	 */
	protected function getFieldValueForEs($field)
	{
		$deniedFields = ['lon', 'lat'];
		if(in_array($field, $deniedFields))
		{
			throw new CannotGetFieldValueForEsException("Cannot get field '$field' for ElasticSearch document array.");
		}
		elseif($field == 'published')
		{
			return empty($this->getPublished()) ? null : $this->getPublished()->format('Y-m-d H:i:s');
		}
		else
		{
			return parent::getFieldValueForEs($field);
		}
	}

	/**
	 * @return array
	 */
	protected function getAdditionalFieldValuesForEs(): array
	{
		return [
			'coords' => [
				'lat' => $this->getLat(),
				'lon' => $this->getLon(),
			]
		];
	}

	/**
	 * @return $this
	 * @throws \Exception
	 */
	public function load()
	{
		/** @TODO throw exception in case object hasn't id or lat or lon */
		$data = [
			'flat' => [
				'id' => $this->getId(),
				'lat' => $this->getLat(),
				'lon' => $this->getLon(),
			],
		];

		try
		{
			$loadedData = $this->loader->setParams($data)->load();
		}
		catch(LoadException $e)
		{
			/** @TODO create specific exception */
			throw new \Exception('Cannot load ad due to: '.$e->getMessage());
		}
		$this->fromArray($loadedData);

		return $this;
	}
}