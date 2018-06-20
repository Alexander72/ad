<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Tests\Services;

use App\Services\Factories\AdFlatFactory;
use App\Entity\Ads\Flat;
use App\Services\AdCache;
use App\Services\Loaders\Avito\FlatLoader;
use PHPUnit\Framework\TestCase;

class AdCacheTest extends TestCase
{
	/**
	 * @var Flat
	 */
	private $flat1;

	/**
	 * @var Flat
	 */
	private $flat2;

	protected function setUp()
	{
		$this->flat1 = (new Flat())->setId(1)->setTitle('Title 1');
		$this->flat2 = (new Flat())->setId(2)->setTitle('Title 2');
	}

	public function testPush()
	{
		$cache = new AdCache();
		$cache->put($this->flat1);
		$this->assertTrue(true);

		return $cache;
	}

	/**
	 * @depends testPush
	 * @param AdCache $cache
	 * @return AdCache
	 */
	public function testIsInCache($cache)
	{
		$this->assertTrue($cache->isInCache($this->flat1), 'Cached object should be in cache');
		$this->assertFalse($cache->isInCache($this->flat2), 'Not cached object should be not in cache');

		return $cache;
	}

	/**
	 * @depends testIsInCache
	 * @param AdCache $cache
	 * @return AdCache
	 */
    public function testIsDifferent($cache)
    {
    	$this->assertFalse($cache->isDifferent($this->flat1), 'Method isDiffer() should return false for cached object that was not modified');
    	$this->assertTrue($cache->isDifferent($this->flat2), 'Method isDiffer() should return true for non cached object');

    	$this->flat1->setTitle('New Title');
	    $this->assertTrue($cache->isDifferent($this->flat1), 'Method isDiffer() should return true for cached object that was modified');

    	return $cache;
    }

	/**
	 * @depends testIsDifferent
	 * @param AdCache $cache
	 */
	public function testDelete($cache)
	{
		$cache->delete($this->flat1);

		$this->assertFalse($cache->isInCache($this->flat1));
		$this->assertFalse($cache->isInCache($this->flat2));
	}
}
