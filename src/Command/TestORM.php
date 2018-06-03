<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 29.05.18
 * Time: 18:09
 */

namespace App\Command;

use App\Entity\Region;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TestORM extends Command
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(
        ContainerInterface $container,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->container = $container;
    }

    protected function configure()
    {
        $this->setName('myTest:ORM');
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
        $em = $this->container->get('doctrine')->getEntityManager();
        $region = new Region();
        $region->setTitle('Msk');

        $em->persist($region);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();
        $output->writeln("ID: {$region->getId()}");
    }
}
