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
use App\Services\Loaders\Avito\FlatsLoader;
use App\Services\AdCache;
use App\Entity\Ads\Flats\AdFlatFactory;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AvitoFlatMskRentInit extends Command
{
	protected $flatsLoader;

	protected $adFlatFactory;

	protected $adRepository;

	protected $adCache;

	/**
	 * @var LoggerInterface
	 */
	private $logger;

	private $isTestMode;

	const REQUEST_COUNT = 'requestCount';

	const IS_TEST_MODE = 'testMode';

	/**
	 * AvitoFlatMskRentInit constructor.
	 * @param FlatsLoader $flatsLoader
	 * @param AdFlatFactory $adFlatFactory
	 * @param AdRepository $adRepository
	 * @param LoggerInterface $logger
	 * @param AdCache $adCache
	 * @param null|string $name
	 */
	public function __construct(
        FlatsLoader $flatsLoader,
        AdFlatFactory $adFlatFactory,
        AdRepository $adRepository,
        LoggerInterface $logger,
        AdCache $adCache,
        ?string $name = null
	) {
		parent::__construct($name);
		$this->flatsLoader = $flatsLoader;
		$this->adFlatFactory = $adFlatFactory;
		$this->adRepository = $adRepository;
		$this->logger = $logger;
		$this->adCache = $adCache;
	}

	protected function configure()
	{
		$this->setName('Avito:flat:initMsk');
		$this->addArgument(self::REQUEST_COUNT, InputArgument::OPTIONAL);
		$this->addOption(self::IS_TEST_MODE);
		$this->setDescription('Load all existed Avito msk flat rent ads via js api');
	}

	protected function execute(InputInterface $input, OutputInterface $output)
	{
	    /** @TODO check migrations before run! */

        $this->isTestMode = $input->getOption(self::IS_TEST_MODE);

		$flatsGenerator = $this->flatsLoader->load();
		$i = 0;
		$requestCount = $input->getArgument(self::REQUEST_COUNT);
		if($requestCount)
		{
			$this->logger->info("Start to load $requestCount flats");
		}

		foreach($flatsGenerator as $flats)
		{
            foreach ($flats as $flatData)
            {
                $i++;
                if($requestCount && $i > $requestCount)
                {
                	$this->logger->info('Exited!');
                	break 2;
                }

	            $flat = $this->adFlatFactory->create();
                $flat->fill($flatData);

	            if($this->adCache->isInCache($flat))
	            {
		            $this->logger->info("$i: id[{$flat->getId()}] - skipped");
		            continue;
	            }

	            $flat->load();

                if(!$this->isTestMode)
                {
                	$this->adRepository->save($flat);
                }

                $this->logger->info("$i: id[{$flat->getId()}] - loaded");
            }
        }
		$this->logger->info('Stop!');
	}
}
