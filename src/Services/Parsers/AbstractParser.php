<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Parsers;

use App\Interfaces\Parsers\ParserInterface;
use App\Traits\ParameterizableTrait;

abstract class AbstractParser implements ParserInterface
{
    use ParameterizableTrait;

    abstract public function parse(string $input);
}
