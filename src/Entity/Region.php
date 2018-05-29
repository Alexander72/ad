<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 18:00
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Region
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
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Region")
     */
    private $parent;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Region
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Region
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Region
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param Region $parent
     * @return self
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }
}
