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
		$this->redisConnect->connect($_ENV['REDIS_HOST'], $_ENV['REDIS_PORT']);
	}

	/**
	 * Put ad to cache
	 * @param AbstractAd $ad
	 * @return AdCache
	 */
	public function put(AbstractAd $ad): self
	{
		list($key, $data) = $this->getKeyAndHash($ad);

		$this->redisConnect->set($key, $data);

		return $this;
	}

	/**
	 * Remove ad from cache
	 * @param AbstractAd $ad
	 * @return AdCache
	 */
	public function delete(AbstractAd $ad): self
	{
		list($key,) = $this->getKeyAndHash($ad);

		$this->redisConnect->delete($key);

		return $this;
	}

	/**
	 * Whether ad differ from cached ad. Method search ad in cache by id.
	 * In case ad doesn't exist in cache return true
	 *
	 * @param AbstractAd $ad
	 * @return bool
	 */
	public function isDifferent(AbstractAd $ad): bool
	{
		list($key,) = $this->getKeyAndHash($ad);

		$cachedHash = $this->redisConnect->get($key);
		if($cachedHash === false)
		{
			return true;
		}

		return $cachedHash !== $this->getHash($ad);
	}

	/**
	 * Check ad exists in cache. Method search ad in cache by id.
	 *
	 * @param AbstractAd $ad
	 * @return bool
	 */
	public function isInCache(AbstractAd $ad): bool
	{
		list($key,) = $this->getKeyAndHash($ad);

		return $this->redisConnect->get($key) !== false;
	}

	/**
	 * Get array with cache key and data
	 *
	 * @param AbstractAd $ad
	 * @return array [key, value]
	 */
	protected function getKeyAndHash(AbstractAd $ad): array
	{
		return [
			$this->getKey($ad::getEsType(), $ad->getId()),
			$this->getHash($ad),
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
	protected function getHash(AbstractAd $ad)
	{
		return sha1(json_encode($ad->toEsArray()));
	}
}
