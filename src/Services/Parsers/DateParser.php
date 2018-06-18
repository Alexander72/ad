<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Parsers;

use App\Exceptions\DateParseException;
use App\Interfaces\Parsers\ParserInterface;

class DateParser implements ParserInterface
{
    /**
     * longest keys should be first!
     */
    const STRING_NUMBER_TO_INT_MAP = [
        'двеннадцат' => 12,
        'одиннадцат' => 11,
        'деcят' => 10,
        'девят' => 9,
        'восем' => 8,
        'сем' => 7,
        'шест' => 6,
        'пят' => 5,
        'чет' => 4,
        'тр' => 3,
        'дв' => 2,
        'од' => 1,
        'нол' => 0,
        'нул' => 0,
    ];

    const TIME_INTERVAL_TRANSLATE_MAP = [
        'секунд' => 'sec',
        'минут' => 'min',
        'час' => 'hour',
    ];

    const MONTHS_TRANSLATE_MAP = [
        'января' => 'january',
        'февраля' => 'february',
        'марта' => 'march',
        'апреля' => 'april',
        'мая' => 'may',
        'июня' => 'june',
        'июля' => 'july',
        'августа' => 'august',
        'сентября' => 'september',
        'октября' => 'october',
        'ноября' => 'november',
        'декабря' => 'december',
        'сегодня' => 'today',
        'позавчера' => 'yesterday yesterday',
        'вчера' => 'yesterday',
        ' в ' => '  ',
    ];

    /**
     * @param string $input
     * @return \DateTime
     * @throws DateParseException
     */
    public function parse(string $input): \DateTime
    {
        $formattedDate = '';
        $modifyDate = '';
        $matches = [];

        $input = trim($input);

        if(in_array($input, ['сейчас', 'только что', '0 минут назад', '0 секунд назад', 'менее минуты назад']))
        {
            $formattedDate = 'now';
        }
        elseif(mb_stripos($input, 'назад'))
        {
            // минут|час|секунд - shortest part of words the ending pass to the .*
            if(preg_match_all('/^(?P<number>\d+ |[абвгдеёжзиклмнопрстуфхцчшщъыьэюя]+ |)(?P<interval>минут|час|секунд).* назад/i', $input, $matches))
            {
                $number = !empty($matches['number'][0]) ? $this->stringToNumber(trim($matches['number'][0])) : 1;
                $interval = isset(self::TIME_INTERVAL_TRANSLATE_MAP[$matches['interval'][0]]) ? self::TIME_INTERVAL_TRANSLATE_MAP[$matches['interval'][0]] : 'error interval';

                $modifyDate = '-' . $number . ' ' . $interval;
            }
            else
            {
                $modifyDate = "Error 'назад'";
            }
        }
        else
        {
            $formattedDate = str_ireplace(
                array_keys(self::MONTHS_TRANSLATE_MAP),
                array_map(function($val){return $val.',';}, array_values(self::MONTHS_TRANSLATE_MAP)),
                $input
            );
        }

        try
        {
            if($modifyDate == $formattedDate && $formattedDate == '')
            {
                throw new \Exception();
            }

            $formattedDate = $formattedDate ? $formattedDate : 'now';
            $result = new \DateTime($formattedDate);

            if($modifyDate)
            {
                $result->modify($modifyDate);
            }

            $now = new \DateTime();

            if($now < $result)
            {
                $result->modify('-1 year');
            }

        }
        catch(\Exception $e)
        {
            throw new DateParseException("Cannot parse datetime '$formattedDate' with modify '$modifyDate' transformed from '$input'");
        }

        return $result;
    }

    protected function stringToNumber(string $input): ?int
    {
        if( ((int) $input) > 0)
        {
            return (int) $input;
        }

        foreach(self::STRING_NUMBER_TO_INT_MAP as $str => $number)
        {
            if(strpos($input, $str) !== false)
            {
                return $number;
            }
        }

        return null;
    }
}
