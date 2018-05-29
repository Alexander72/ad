<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:56
 */

namespace App\Entity\Flats;

use App\Entity\AbstractAd;

class Flat extends AbstractAd
{
	protected $id;
	protected $title;
	protected $url;
	protected $price;
	protected $type;
	protected $address;
	protected $floor;
	protected $floorsTotal;
	protected $houseType;
	protected $rooms;
	protected $area;
	protected $areaKitchen;
	protected $areaLive;
	protected $data;
}