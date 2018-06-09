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
use App\Services\Loaders\Avito\ItemsLoader;
use App\Services\Avito\Flat\Rent\Msk\Loaders\Item\ItemLoader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AvitoFlatMskRentInit extends Command
{
	protected $flatsLoader;

	protected $flatLoader;

	protected $adRepository;

	const REQUEST_COUNT = 'requestCount';

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
		$this->addArgument(self::REQUEST_COUNT, InputArgument::OPTIONAL);
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

		$flatsGenerator = $this->flatsLoader->load();
		$i = 0;
		$requestCount = $input->getArgument(self::REQUEST_COUNT);

		foreach($flatsGenerator as $flats)
		{
            foreach ($flats as $flat)
            {
                $i++;
                if($requestCount && $i > $requestCount)
                {
                	$output->writeln('Exited!');
                	break 2;
                }
                $output->write("$i: ");

                $existedFlat = $this->adRepository->findOneBy(['site' => 'avito', 'siteId' => $flat['id']]);

                if ($existedFlat !== null)
                {
                    $output->writeln('skipped');
                    continue;
                }

                $flat = $this->flatLoader->load($flat);
                $flat = new Flat($flat);
                $this->adRepository->save($flat);
                $output->writeln("loaded");
            }
        }
		$output->writeln('Stop!');
	}
}
