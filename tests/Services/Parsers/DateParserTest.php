<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Tests\Services\Parsers;

use App\Exceptions\DateParseException;
use App\Services\Parsers\DateParser;
use PHPUnit\Framework\TestCase;
use PHPUnit\Runner\Exception;

class DateParserTest extends TestCase
{
    /**
     * @dataProvider legalDateDataProvider
     */
    public function testParse($string, $expected)
    {
        $dateParser = new DateParser();
        $this->assertEquals($expected->format('Y-m-d H:i'), $dateParser->parse($string)->format('Y-m-d H:i'));
    }

    /**
     * @dataProvider exceptionDateDataProvider
     */
    public function testParseExceptions($invalidString)
    {
        $this->expectException(DateParseException::class);

        $dateParser = new DateParser();
        $dateParser->parse($invalidString);
    }

    public function legalDateDataProvider()
    {
        $y = date('Y');

        $h = date('H');
        $m = date('i');

        $result = [
            ['только что', new \DateTime()],
            ['сейчас', new \DateTime()],

            ['минуту назад', (new \DateTime())->modify('-1 min')],
            ['одну минуту назад', (new \DateTime())->modify('-1 min')],
            ['1 минуту назад', (new \DateTime())->modify('-1 min')],
            ['две минуты назад', (new \DateTime())->modify('-2 min')],
            ['2 минуты назад', (new \DateTime())->modify('-2 min')],
            ['три минуты назад', (new \DateTime())->modify('-3 min')],
            ['3 минуты назад', (new \DateTime())->modify('-3 min')],
            ['четыре минуты назад', (new \DateTime())->modify('-4 min')],
            ['пять минут назад', (new \DateTime())->modify('-5 min')],
            ['5 минут назад', (new \DateTime())->modify('-5 min')],
            ['45 минут назад', (new \DateTime())->modify('-45 min')],
            ['60 минут назад', (new \DateTime())->modify('-60 min')],

            ['час назад', (new \DateTime())->modify('-1 hour')],
            ['два часа назад', (new \DateTime())->modify('-2 hour')],
            ['2 часа назад', (new \DateTime())->modify('-2 hour')],
            ['три часа назад', (new \DateTime())->modify('-3 hour')],
            ['3 часа назад', (new \DateTime())->modify('-3 hour')],
            ['четыре часа назад', (new \DateTime())->modify('-4 hour')],
            ['4 часа назад', (new \DateTime())->modify('-4 hour')],
            ['пять часов назад', (new \DateTime())->modify('-5 hour')],
            ['5 часов назад', (new \DateTime())->modify('-5 hour')],
            ['шесть часов назад', (new \DateTime())->modify('-6 hour')],
            ['6 часов назад', (new \DateTime())->modify('-6 hour')],
            ['двеннадцать часов назад', (new \DateTime())->modify('-12 hour')],
            ['12 часов назад', (new \DateTime())->modify('-12 hour')],

            ['сегодня в 00:00', new \DateTime('0:00')],
            ["сегодня в $h:$m", new \DateTime()],

            ['вчера в 18:34', (new \DateTime('18:34'))->modify('-1 day')],
            ['вчера в 04:04', (new \DateTime('04:04'))->modify('-1 day')],
            ['вчера в 00:00', (new \DateTime('0:00'))->modify('-1 day')],
            ['вчера в 23:59', (new \DateTime('23:59'))->modify('-1 day')],

            ['31 декабря в 23:59', ($tmp = new \DateTime("$y-12-31 23:59")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],
            ['31 декабря в 00:00', ($tmp = new \DateTime("$y-12-31 00:00")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],
            ['31 декабря в 17:39', ($tmp = new \DateTime("$y-12-31 17:39")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],
            ['31 декабря в 03:59', ($tmp = new \DateTime("$y-12-31 03:59")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],

            ['13 июня в 23:59', ($tmp = new \DateTime("$y-06-13 23:59")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],
            ['12 мая в 00:00', ($tmp = new \DateTime("$y-05-12 00:00")) && $tmp > new \DateTime() ? $tmp->modify('-1 year') : $tmp],
        ];

        if((new \DateTime()) > (new \DateTime('04:04')))
            $result[] = ['сегодня в 04:04', new \DateTime('04:04')];

        if((new \DateTime()) > (new \DateTime('13:34')))
            $result[] = ['сегодня в 13:34', new \DateTime('13:34')];

        return $result;
    }

    public function exceptionDateDataProvider()
    {
        return [
            ['завтра'],
            ['потом'],
            ['12 никогда 20:00'],
            ['11 февоаля 12:00'],
            ['28 февраля 25:00'],
        ];
    }
}
