<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 15:50
 */

namespace App\Interfaces\Loaders;

use App\Exceptions\LoadException;

interface LoaderInterface
{
	/**
	 * Load data from site and parse it from string. Returns parsed response data
     *
     * @throws LoadException
	 */
	public function load();

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @param array $params
     * @return LoaderInterface
     */
    public function setParams(array $params);
}