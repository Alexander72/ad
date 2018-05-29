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
			'id' => 'my_id_new',
			'body' => ['testFieldNew' => 'foo']
		];

		$response = $client->index($params);
		$output->writeln(print_r($response, 1));
	}
}