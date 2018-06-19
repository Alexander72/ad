<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 13:12
 */

namespace App\Entity\Ads;

use App\Exceptions\CannotGetFieldValueForEsException;
use App\Interfaces\Loaders\LoaderInterface;
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
     * @ORM\Column(type="string", length=255)
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
     * @ORM\Column(type="integer")
     */
    protected $siteId;

	/**
	 * @ORM\Column(type="datetime")
	 */
	protected $published;

	/**
	 * @ORM\Column(type="string")
	 */
	protected $description;

    /**
     * @var LoaderInterface
     */
    protected $loader;

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

    /**
     * @return mixed
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

	/**
	 * @return mixed
	 */
	public function getPublished()
	{
		return $this->published;
	}

	/**
	 * @param mixed $published
	 * @return AbstractAd
	 */
	public function setPublished($published)
	{
		$this->published = $published;

		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * @param mixed $description
	 * @return AbstractAd
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

    /**
     * @return LoaderInterface|null
     */
	public function getLoader(): ?LoaderInterface
	{
		return $this->loader;
	}

    /**
     * @param LoaderInterface $loader
     * @return $this
     */
	public function setLoader(LoaderInterface $loader)
	{
		$this->loader = $loader;

		return $this;
	}

    /**
     * @param $siteId
     * @return AbstractAd
     */
    public function setSiteId($siteId): self
    {
        $this->siteId = $siteId;
        return $this;
    }

	public function fill(array $data)
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

    abstract public static function getEsType();

    /**
     * @return array
     */
    public function toEsArray(): array
    {
        $result = [];

        foreach($this as $field => $value)
        {
            try
            {
                $result[$field] = $this->getFieldValueForEs($field);
            }
            catch(CannotGetFieldValueForEsException $e)
            {
                continue;
            }
        }

        $result = array_merge($result, $this->getAdditionalFieldValuesForEs());

        return $result;
    }

    /**
     * @param $field
     * @return mixed
     * @throws CannotGetFieldValueForEsException
     */
    protected function getFieldValueForEs($field)
    {
        $method = 'get' . ucfirst($field);
        if(method_exists($this, $method))
        {
            return $this->$method();
        }
        else
        {
            throw new CannotGetFieldValueForEsException("Cannot get field '$field' for ElasticSearch document array.");
        }
    }

    /**
     * @return array
     */
    protected function getAdditionalFieldValuesForEs(): array
    {
        return [];
    }
}
