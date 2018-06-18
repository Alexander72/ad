<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Interfaces\Parsers;


interface ParserInterface
{
    /**
     * Parse input string to array
     *
     * @param string $input
     * @return array
     */
    public function parse(string $input): array;
}
