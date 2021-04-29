<?php

namespace TNLMedia\MemberSDK\Nodes;

use ArrayIterator;
use IteratorAggregate;

class SearchResult extends Node implements IteratorAggregate
{
    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return $this->getList();
    }

    /**
     * Result list
     *
     * @return ArrayIterator
     */
    public function getList()
    {
        return new ArrayIterator($this->getArrayAttributes('list'));
    }

    /**
     * Result count
     *
     * @return int|void
     */
    public function getCount()
    {
        return $this->getList()->count();
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
