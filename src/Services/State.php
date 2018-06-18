<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18.06.18
 * Time: 23:36
 */

namespace App\Services;


class State
{
	static private $state;

	/** @var float */
	protected $topLat;

	/** @var float */
	protected $bottomLat;

	/** @var float */
	protected $leftLon;

	/** @var float */
	protected $rightLon;

	/** @var int */
	protected $pid;

	/** @var string */
	protected $proc;

	private function __construct()
	{
		$this->setPid(getmypid());
	}

	/**
	 * @return float
	 */
	public function getTopLat(): float
	{
		return $this->topLat;
	}

	/**
	 * @param float $topLat
	 * @return State
	 */
	public function setTopLat(float $topLat): State
	{
		$this->topLat = $topLat;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getBottomLat(): float
	{
		return $this->bottomLat;
	}

	/**
	 * @param float $bottomLat
	 * @return State
	 */
	public function setBottomLat(float $bottomLat): State
	{
		$this->bottomLat = $bottomLat;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLeftLon(): float
	{
		return $this->leftLon;
	}

	/**
	 * @param float $leftLon
	 * @return State
	 */
	public function setLeftLon(float $leftLon): State
	{
		$this->leftLon = $leftLon;

		return $this;
	}

	/**
	 * @return float
	 */
	public function getRightLon(): float
	{
		return $this->rightLon;
	}

	/**
	 * @param float $rightLon
	 * @return State
	 */
	public function setRightLon(float $rightLon): State
	{
		$this->rightLon = $rightLon;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPid(): int
	{
		return $this->pid;
	}

	/**
	 * @param int $pid
	 * @return State
	 */
	public function setPid(int $pid): State
	{
		$this->pid = $pid;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getProc(): string
	{
		return $this->proc;
	}

	/**
	 * @param string $proc
	 * @return State
	 */
	public function setProc(string $proc): State
	{
		$this->proc = $proc;

		return $this;
	}

	static public function getState()
	{
		if(!self::$state)
		{
			self::$state = new self();
		}

		return self::$state;
	}
}