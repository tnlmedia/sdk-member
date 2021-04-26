<?php

namespace TNLMedia\MemberSDK\Nodes;

class SearchResult extends Node
{
    /**
     * Result list
     *
     * @return array
     */
    public function getList()
    {
        return $this->getArrayAttributes('list');
    }

    /**
     * Result count
     *
     * @return int|void
     */
    public function getCount()
    {
        return count($this->getArrayAttributes('list'));
    }

    /**
     * Total count
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->getIntegerAttributes('total');
    }
}
