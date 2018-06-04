<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 04.06.18
 * Time: 0:25
 */

namespace App\Command;

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
		$client = ClientBuilder::create()->build();

		for($i = 0; $i < 1000; $i++)
		{
			list($lat, $lon) = $this->generatePoint();

			$coords = [$lon, $lat];
			$area = rand(13, 119);
            $correlation = $this->getCorrelation($lon, $lat);
            $unitCost = rand(80000, 110000) + $correlation * 100000;
			$cost = $area * $unitCost;
			$params = [
				'index' => 'test_map',
				'type' => 'flats',
				'id' => $i,
				'body' => [
					'title' => "title_$i",
					'coords' => $coords,
					'cost' => $cost,
					'unitCost' => $unitCost,
					'area' => $area,
                    'correlation' => $correlation,
				],
			];



			$response = $client->index($params);
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

	    $centerX = $widthDistance / 2 + self::LEFT;
	    $centerY = $heightDistance / 2 + self::DOWN;

	    $distance = $this->distance($x, $y, $centerX, $centerY);
	    $correlation = 1 - max(0, min(1, $distance / ($widthDistance / 2)));

	    return $correlation;
	}

    private function distance($x1, $y1, $x2, $y2)
    {
        return sqrt(($x1 - $x2) ** 2 + ($y1 - $y2) ** 2);
	}
}
