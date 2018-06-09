<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Loaders\Avito\Http;


class Sender
{
    /**
     * Request per second count allowed to send without ip blocking by Avito
     */
    const NON_BLOCKED_REQUESTS_PER_SECOND = 5;

    /**
     * Request duration in ms
     */
    const REQUEST_DURATION = 25;

    /**
     * Avito base url
     */
    const BASE_URL = 'https://www.avito.ru';

    /**
     * @var float
     */
    static protected $lastSendTime = 0;

    /**
     * @param $url
     * @return string
     */
    public function send($url): string
    {
        $url = self::BASE_URL . $url;

        $this->waitBeforeSend();

        $result = file_get_contents($url);

        self::$lastSendTime = microtime(1);

        return $result;
    }

    private function waitBeforeSend(): self
    {
        $rps = self::NON_BLOCKED_REQUESTS_PER_SECOND;

        // The delay in milliseconds that must be waited between requests as
        // if the requests are sent in a row without delay for processing
        $delayInMilliseconds = (1000 - $rps * self::REQUEST_DURATION) / $rps;

        // delay correction because of request processing
        $delayInMilliseconds = max(0, $delayInMilliseconds - (microtime(1) - self::$lastSendTime) * 1000);
        usleep(1000 * $delayInMilliseconds);

        return $this;
    }
}