<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 14:52
 */

namespace App\Console;

use App\Entity\Flats\Flat;

use App\Services\Avito\Flat\Rent\Msk\Loaders\Items\ItemsLoader;
use App\Services\Avito\Flat\Rent\Msk\Loaders\Item\ItemLoader;

use App\Services\Avito\Flat\Rent\Msk\Formatters\ItemsFormatter;
use App\Services\Avito\Flat\Rent\Msk\Formatters\ItemFormatter;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AvitoFlatMskRentInit extends Command
{
	protected $flatsLoader;

	protected $flatLoader;

	protected $flatsFormatter;

	protected $flatFormatter;

	public function __construct(
		ItemsLoader $flatsLoader,
		ItemLoader $flatLoader,
		ItemsFormatter $flatsFormatter,
		ItemFormatter $flatFormatter,
		?string $name = null
	) {
		parent::__construct($name);
		$this->flatsLoader = $flatsLoader;
		$this->flatLoader = $flatLoader;
		$this->flatsFormatter = $flatsFormatter;
		$this->flatFormatter = $flatFormatter;
	}


	protected function configure()
	{
		$this->setName('Avito:flat:initMsk');
		$this->setDescription('Load all existed Avito msk flat ads via js api');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$flats = $this->flatsLoader->load();
		$output->writeln(print_r($flats, 1));
		/*foreach($flats as $flat)
		{
			$flat = $this->flatLoader->load($flat);
			$flat = new Flat($flat);
		}*/
	}
}
