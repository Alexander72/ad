<?php

namespace App\Interfaces\Loaders;

interface SenderInterface
{
    /**
     * Send HTTP request
     *
     * @param string $url request URL
     * @return string http response
     */
    public function send(string $url): string;

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @param array $params
     * @return SenderInterface
     */
    public function setParams(array $params);
}
