<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.06.18
 * Time: 0:25
 */

namespace App\Console;

use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestKibanaMap extends Command
{
	const UP = 55.892967;
	const LEFT = 37.355072;
	const DOWN = 55.590704;
	const RIGHT = 37.841217;

	protected function configure()
	{
		$this->setName('myTest:generate:kibana');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		// kibana wms url: https://a.tile.openstreetmap.org/{z}/{x}/{y}.png
		//$client = ClientBuilder::create()->build();

		for($i = 0; $i < 10; $i++)
		{
			list($lat, $lon) = $this->generatePoint();

			$coords = [$lon, $lat];
			$area = rand(13, 119);
			$unitCost = rand(80000, 150000) + $this->getCorrelation($lat, $lon) * 200000;
			$cost = $area * $unitCost;
			$params = [
				'index' => 'testMap',
				'type' => 'flats',
				'id' => $i,
				'body' => [
					'title' => "title_$i",
					'coords' => $coords,
					'cost' => $cost,
					'unitCost' => $unitCost,
					'area' => $area,
				],
			];

			//$response = $client->index($params);
			$output->writeln(print_r($params['body'], 1));
		}
	}

	protected function generatePoint()
	{
		$lat = rand(self::UP * 1000000, self::DOWN * 1000000) / 1000000;
		$lon = rand(self::LEFT * 1000000, self::RIGHT * 1000000) / 1000000;

		return [$lat, $lon];
	}

	protected function getCorrelation($x, $y)
	{
		$widthDistance = self::RIGHT - self::LEFT;
		$heightDistance = self::UP - self::DOWN;

		$result = 1 - abs($x - (self::LEFT + $widthDistance / 2)) / (self::LEFT + $widthDistance / 2);
		$result += 1 - abs($y - (self::UP + $heightDistance / 2)) / (self::UP + $heightDistance / 2);

		return $result;
	}
}
