<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 17:23
 */

namespace App\Entity\Flats;

use App\Entity\AbstractAd;
use Doctrine\ORM\Mapping as ORM;

class AbstractFlatAd extends AbstractAd
{
    protected $roomCount;
    protected $floor;
    protected $floorCount;
    protected $address;
    protected $houseType;
    protected $area;
    protected $areaKitchen;
    protected $areaLive;
}

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    public function getId()
    {
        return $this->id;
    }

    // ... getter and setter methods
}