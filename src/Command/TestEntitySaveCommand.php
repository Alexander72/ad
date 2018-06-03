<?php

namespace App\Command;

use App\Entity\Ads\AdRepository;
use App\Entity\Ads\Flats\Flat;
use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestEntitySaveCommand extends Command
{
    protected static $defaultName = 'myTest:entity-save';

    /**
     * @var AdRepository
     */
    protected $adRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container,
        AdRepository $adRepository,
        ?string $name = null
    ) {
        parent::__construct($name);
        $this->container = $container;
        $this->adRepository = $adRepository;
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var EntityManager $em */
        $em = $this->container->get('doctrine')->getEntityManager();
        $regionRepository = $em->getRepository(Region::class);

        $region = $regionRepository->findOneBy(['title' => 'Msk']);

        $data = [
            'id' => 312312,
            'lat' => 34.53771,
            'lon' => 56.826371,
            'address' => 'Тверская, 1',
            'area' => 56,
            'type' => 'rent',
            'site' => 'avito',
            'region' => $region,
            'title' => 'Test',
            'price' => 4000000,
            'floor' => null,
            'url' => '/test/url?param',
            'data' => [
                'test' => 1313,
                'foo' => true,
                'bar' => ['a', 'b', 'c'],
            ],
        ];

        $flat = new Flat($data);

        $this->adRepository->save($flat);

        $io->success('Hello!');
    }
}
