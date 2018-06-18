<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Tests\Services\Parsers\Avito;

use App\Services\Parsers\Avito\FlatParser;
use PHPUnit\Framework\TestCase;

class FlatTest extends TestCase
{
    /**
     * @dataProvider htmlDataProvider
     */
    public function testParse($input,$expected)
    {
        $parser = new FlatParser();

        $this->assertEquals($expected, $parser->parse($input));
    }

    public function htmlDataProvider()
    {
        $flat = file_get_contents(__DIR__ . '/flat.html');

        return [
            [$flat, ['title' => '2-к квартира, 40 м², 6/9 эт.']],
        ];
    }

}
