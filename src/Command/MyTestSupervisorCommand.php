<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MyTestSupervisorCommand extends Command
{
    protected static $defaultName = 'myTest:supervisor';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        while(true)
        {
	        $tmp = rand(30, 60);
	        $output->writeln("value is $tmp");
	        if($tmp == 42)
	        {
	        	$output->writeln('Exit.');
	        	break;
	        }

	        sleep(1);
        }
    }
}
