<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 27.05.18
 * Time: 14:52
 */

namespace App\Command;


use App\Entity\Ads\AdRepository;
use App\Entity\Ads\Flats\Flat;
use App\Services\Avito\Flat\Rent\Msk\Loaders\Items\ItemsLoader;
use App\Services\Avito\Flat\Rent\Msk\Loaders\Item\ItemLoader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AvitoFlatMskRentInit extends Command
{
	protected $flatsLoader;

	protected $flatLoader;

	protected $adRepository;

    /**
     * AvitoFlatMskRentInit constructor.
     * @param ItemsLoader $flatsLoader
     * @param ItemLoader $flatLoader
     * @param AdRepository $adRepository
     * @param null|string $name
     */
	public function __construct(
		ItemsLoader $flatsLoader,
		ItemLoader $flatLoader,
        AdRepository $adRepository,
		?string $name = null
	) {
		parent::__construct($name);
		$this->flatsLoader = $flatsLoader;
		$this->flatLoader = $flatLoader;
		$this->adRepository = $adRepository;
	}


	protected function configure()
	{
		$this->setName('Avito:flat:initMsk');
		$this->setDescription('Load all existed Avito msk flat rent ads via js api');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
        /**
         * PUT ad
         * {}
         *
         * PUT ad/_mapping/flat
         * {
         *  "properties": {
         *   "coords": {
         *    "type": "geo_point"
         *   }
         *  }
         * }
         *
         */

		$flats = $this->flatsLoader->load();
		$i = 0;

		foreach($flats as $flat)
		{
		    if($i++ > 1) break;
            $flat = $this->flatLoader->load($flat);
            $flat = new Flat($flat);
            $this->adRepository->save($flat);
            $output->writeln(print_r($flat->toEsArray(), 1));
		}
	}
}
