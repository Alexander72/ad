<?php

namespace App\Traits;


trait ParameterizableTrait
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function setParams(array $params): self
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * @param $paramName
     * @return null
     */
    public function getParam($paramName)
    {
        return $this->params[$paramName] ?? null;
    }
}