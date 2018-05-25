<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 25.05.18
 * Time: 19:14
 */

namespace App\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendRequestCommand extends Command
{
    const REQUESTS_PER_SECOND = 3;
    const DURATION_IN_SECONDS = 300;
    const REPORT_TIME = 5;

    const REQUEST_DURATION = 25;

    private $requestTimes = [];

    protected function configure()
    {
        $this->setName('ad:load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = "https://www.avito.ru/js/catalog/coords?s=101&token%5B102589681436%5D=67cd3147a23eb8f&sgtd=&category_id=26&location_id=637640&name=&params%5B203%5D=&pmin=&pmax=&geo=54.05115317387588%2C35.69677118458431%2C56.71747104329763%2C38.6630797783343%2C8%2Cf";
        $url = "https://www.avito.ru/js/catalog/items?id=1044600709&lat=54.89726359110567&lng=38.32359993025392&priceDimensionValue=-1";
        $start = microtime(true);
        $lastSecond = $start;
        $sendedRequestCount = 0;
        $secondsCountLeft = 0;

        while(microtime(true) - $start <= self::DURATION_IN_SECONDS)
        {
            if(microtime(true) - $lastSecond > 1)
            {
                $lastSecond = microtime(true);
                $sendedRequestCount = 0;
                $secondsCountLeft++;

                if($secondsCountLeft % self::REPORT_TIME == 0)
                {
                    $output->writeln(floor(microtime(true) - $start).' seconds left');
                }
            }

            if($sendedRequestCount < self::REQUESTS_PER_SECOND)
            {
                $output->write('Try send..  ');
                $output->writeln(isset($this->getAd($url)['items']) ? 'Ok '.microtime(1) : 'Error');
                $sendedRequestCount++;
            }

            usleep((1000 - self::REQUEST_DURATION * self::REQUESTS_PER_SECOND) / self::REQUESTS_PER_SECOND * 1000);
        }

        $output->writeln('Request AVG time: '. $this->getAvgTime());
        $output->writeln('Request count: ' . count($this->requestTimes));
    }

    public function getAd($url)
    {
        $start = microtime(true);
        $result = json_decode(file_get_contents($url), 1);
        $finish = microtime(true);

        $this->addRequestTime($start, $finish);

        return $result;
    }

    private function addRequestTime($start, $finish)
    {
        $this->requestTimes[] = [$start, $finish];
    }

    private function getAvgTime()
    {
        return array_reduce($this->requestTimes, function($sum, $duration){
            return $sum + ($duration[1] - $duration[0]);
        }) / count($this->requestTimes);
    }
}
