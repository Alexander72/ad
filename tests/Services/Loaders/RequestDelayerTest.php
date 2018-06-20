<?php

namespace App\Tests\Services\Loaders;

use App\Services\Loaders\Avito\Http\Sender;
use App\Services\Loaders\RequestDelayer;
use PHPUnit\Framework\TestCase;

class RequestDelayerTest extends TestCase
{

    public function testGetDelayer()
    {
        $delayer1 = RequestDelayer::getDelayer();
        $delayer2 = RequestDelayer::getDelayer();
        $delayer3 = RequestDelayer::getDelayer();

        $this->assertEquals($delayer1, $delayer2);
        $this->assertEquals($delayer3, $delayer2);
    }

    public function testWait()
    {
        $delayer = RequestDelayer::getDelayer();

        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertLessThanOrEqual(1, (microtime(1) - $time) * 1000, 'Delayer should not wait at first run');

        // 160ms = (1000ms - 5rps*25ms)/5rps - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(160, (microtime(1) - $time) * 1000, 'Delayer should wait at least 160ms since the previous run, given the processing time 0ms and assertion time 15ms');
        $this->assertLessThanOrEqual(176, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 176ms since previous run, given the processing time 0ms');

        // 160ms = (1000ms - 5rps*25ms)/5rps - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(160, (microtime(1) - $time) * 1000, 'Delayer should wait at least 160ms since the previous run, given the processing time 0ms and assertion time 15ms');
        $this->assertLessThanOrEqual(176, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 176ms since previous run, given the processing time 0ms');

        usleep(1000 * 95);

        // 65ms = (1000ms - 5rps*25ms)/5rps - 95ms - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(65, (microtime(1) - $time) * 1000, 'Delayer should wait at least 65ms since the previous run, given the processing time 95ms and assertion time 15ms');
        $this->assertLessThanOrEqual(81, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 81ms since the previous run, given the processing time 95ms');

        usleep(1000 * 150);

        // 10ms = (1000ms - 5rps*25ms)/5rps - 150ms - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(10, (microtime(1) - $time) * 1000, 'Delayer should wait at least 10ms since the previous run, given the processing time 150msand assertion time 15ms');
        $this->assertLessThanOrEqual(26, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 26ms since the previous run, given the processing time 150ms');

        usleep(1000 * 250);

        // 0ms: (1000ms - 5rps*25ms)/5rps - 250ms = -75 < 0
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertLessThanOrEqual(1, (microtime(1) - $time) * 1000, 'Delayer should not wait since the previous run, given the processing time more than 175ms');

        usleep(1000 * 350);

        // 0ms: (1000ms - 5rps*25ms)/5rps - 350ms = -175 < 0
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertLessThanOrEqual(1, (microtime(1) - $time) * 1000, 'Delayer should not wait since the previous run, given the processing time more than 175ms');

        // 160ms = (1000ms - 5rps*25ms)/5rps - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(160, (microtime(1) - $time) * 1000, 'Delayer should wait at least 160ms since the previous run, given the processing time 0ms and assertion time 15ms');
        $this->assertLessThanOrEqual(176, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 176ms since previous run, given the processing time 0ms');

        usleep(1000 * 95);

        // 65ms = (1000ms - 5rps*25ms)/5rps - 95ms - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(65, (microtime(1) - $time) * 1000, 'Delayer should wait at least 65ms since the previous run, given the processing time 95ms and assertion time 15ms');
        $this->assertLessThanOrEqual(81, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 81ms since the previous run, given the processing time 95ms');

        usleep(1000 * 350);

        // 0ms: (1000ms - 5rps*25ms)/5rps - 350ms = -175 < 0
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertLessThanOrEqual(1, (microtime(1) - $time) * 1000, 'Delayer should not wait since the previous run, given the processing time more than 175ms');

        // 160ms = (1000ms - 5rps*25ms)/5rps - 15ms/** assertion delay */
        $time = microtime(1);
        $delayer->wait(Sender::class);
        $this->assertGreaterThanOrEqual(160, (microtime(1) - $time) * 1000, 'Delayer should wait at least 160ms since the previous run, given the processing time 0ms and assertion time 15ms');
        $this->assertLessThanOrEqual(176, (microtime(1) - $time) * 1000, 'Delayer should wait no longer than 176ms since previous run, given the processing time 0ms');
    }
}
