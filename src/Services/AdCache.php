<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 16.06.18
 * Time: 1:33
 */

namespace App\Services;


use App\Entity\Ads\AbstractAd;
use App\Entity\Ads\AdRepository;

class AdCache
{
	/**
	 * @var \Redis
	 */
	protected $redisConnect;

	/**
	 * AdCache constructor.
	 */
	public function __construct()
	{
		$this->redisConnect = new \Redis();
		$this->redisConnect->connect($_SERVER['REDIS_HOST'], $_SERVER['REDIS_PORT']);
	}

	/**
	 * Put ad to cache
	 * @param AbstractAd $ad
	 * @return AdCache
	 */
	public function put(AbstractAd $ad): self
	{
		list($key, $data) = $this->getKeyValue($ad);

		$this->redisConnect->set($key, $data);

		return $this;
	}

	/**
	 * Remove ad from cache
	 * @param $type
	 * @param $id
	 * @return AdCache
	 */
	public function delete($type, $id): self
	{
		$key = $this->getKey($type, $id);

		$this->redisConnect->delete($key);

		return $this;
	}

	/**
	 * Whether ad differ from cached ad. Method search ad in cache by id.
	 * In case ad doesn't exist in cache return false
	 *
	 * @param AbstractAd $ad
	 * @return bool
	 */
	public function isDifferent(AbstractAd $ad): bool
	{
		list($key, $data) = $this->getKeyValue($ad);

		if($this->redisConnect->get($key) === false)
		{
			return true;
		}

		return $data === $this->getValue($ad);
	}

	/**
	 * Check ad exists in cache. Method search ad in cache by id.
	 *
	 * @param AbstractAd $ad
	 * @return bool
	 */
	public function isInCache(AbstractAd $ad): bool
	{
		list($key,) = $this->getKeyValue($ad);

		return $this->redisConnect->get($key) === false;
	}

	/**
	 * Get array with cache key and data
	 *
	 * @param AbstractAd $ad
	 * @return array [key, value]
	 */
	protected function getKeyValue(AbstractAd $ad): array
	{
		return [
			$this->getKey($ad::getEsType(), $ad->getId()),
			$this->getValue($ad),
		];
	}

	/**
	 * Get cache key by type and id
	 * @param $type
	 * @param $id
	 * @return string
	 */
	protected function getKey($type, $id): string
	{
		return AdRepository::INDEX.'_'.$type.'_'.$id;
	}

	/**
	 * Get ad hash
	 * @param AbstractAd $ad
	 * @return string
	 */
	protected function getValue(AbstractAd $ad)
	{
		return sha1(json_encode($ad->toEsArray()));
	}
}
