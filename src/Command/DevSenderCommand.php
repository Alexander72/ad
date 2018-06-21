<?php

namespace App\Command;

use App\Services\Loaders\Avito\Http\Sender;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DevSenderCommand extends Command
{
    protected static $defaultName = 'dev:sender';

    protected $sender;

	public function __construct(
		Sender $sender,
		?string $name = null
	) {
		parent::__construct($name);
		$this->sender = $sender;
	}


	protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$url = '/moskva/kvartiry/4-k_kvartira_174_m_2732_et._1084797318';
    	$response = $this->sender->send($url);
	    //
	    // $url = '/js/catalog/items?id=1213915307&lat=55.64525999264896&lng=37.47585157657834';
	    // $response = $this->sender->send($url);
	    //
	    // $url = '/moskva/kvartiry/4-k_kvartira_100_m_418_et._1213915307';
	    // $response = $this->sender->send($url);
	    //
	    // $url = '/js/catalog/items?id=582427027&lat=55.66313627585704&lng=37.48284166167894';
	    // $response = $this->sender->send($url);

	    //$output->writeln($response);
    }
}
