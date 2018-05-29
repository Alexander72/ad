<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 30.05.18
 * Time: 0:50
 */

namespace App\Console;


use Elasticsearch\ClientBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestElastic extends Command
{
	protected function configure()
	{
		$this->setName('myTest:elastic');
	}

	/**
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return int|null|void
	 * @throws \Doctrine\ORM\ORMException
	 * @throws \Doctrine\ORM\OptimisticLockException
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$client = ClientBuilder::create()->build();

		$params = [
			'index' => 'my_index',
			'type' => 'my_type',
			'id' => 34234234,
			'body' => [
				'testFieldNew' => 'foo',
				'complex_data' => [
					'lat' => 56.234232423,
					'lon' => 34.234232423,
					'title' => 'Hello World!',
					'floor' => [
						'id' => 3,
						'name' => 'three',
					]
				],
			],
		];

		$response = $client->index($params);
		$output->writeln(print_r($response, 1));
	}
}