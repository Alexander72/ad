<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InitCacheCommand extends Command
{
    protected static $defaultName = 'myTest:initCache';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
	    $redis = new \Redis();
	    $redis->connect($_SERVER['REDIS_HOST'], $_SERVER['REDIS_PORT']);
	    $redis->set('test_key', 'test_value');
	    $value = $redis->get('test_key');

        $io->success("Success! Value is $value");
    }
}
