<?php
/**
 * @category  Cyberhull
 * @package   Package
 * @copyright Copyright (C) 2018 Cyberhull
 * @author    Alexander Volkov <alexandr.volkov@cyberhull.com>
 */

namespace App\Services\Parsers\Avito;
require_once($_SERVER['DOCUMENT_ROOT'] . 'vendor/electrolinux/phpquery/phpQuery/phpQuery.php');

use App\Interfaces\Parsers\ParserInterface;

class FlatParser implements ParserInterface
{
    protected $pq = 'pq';

    public function parse(string $input): array
    {
        \phpQuery::newDocument($input);

        $titleElement = pq('.item-view-page-layout .item-view .title-info-title-text');

        return [
            'title' => $titleElement->text()
        ];
    }
}
