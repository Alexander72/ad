<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Interfaces\Parsers;


use App\Exceptions\ParseException;

interface ParserInterface
{
    /**
     * Parse input string to array
     *
     * @param string $input
     * @throws ParseException
     */
    public function parse(string $input);
}
