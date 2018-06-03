<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 13:12
 */

namespace App\Entity\Ads;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractAd
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
	protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $title;

    /**
     * @ORM\Column(type="integer")
     */
    protected $price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $site;

    /**
     * @ORM\ManyToOne(targetEntity="\App\Entity\Region")
     */
    protected $region;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected $data;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $url;


    /**
     * AbstractAd constructor.
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $field => $value)
        {
            $method = 'set' . ucfirst($field);
            if(method_exists($this, $method))
            {
                $this->$method($value);
            }
        }
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return AbstractAd
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     * @return AbstractAd
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     * @return AbstractAd
     */
    public function setPrice($price)
    {
        $this->price = $price;
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
     * @return AbstractAd
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
     * @return AbstractAd
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
     * @return AbstractAd
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
     * @return AbstractAd
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     * @return AbstractAd
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    abstract public static function getEsType();

    public function toArray(): array
    {
        $result = [];

        foreach($this as $field => $value)
        {
            /** @TODO make refactoring. Maybe you should use new method and Exception for fields that shouldn't be in result array? */
            $method = 'get' . ucfirst($field);
            if(method_exists($this, $method)) {
                $result[$field] = $this->$method();
            }
        }

        return $result;
    }
}
