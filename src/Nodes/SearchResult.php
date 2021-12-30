<?php

namespace TNLMedia\MemberSDK\Nodes;

use ArrayIterator;
use IteratorAggregate;

class SearchResult extends Node implements IteratorAggregate
{
    /**
     * {@inheritDoc}
     */
    public function getIterator(): ArrayIterator
    {
        return $this->getList();
    }

    /**
     * Result list
     *
     * @return ArrayIterator
     */
    public function getList(): ArrayIterator
    {
        return new ArrayIterator($this->getArrayAttributes('list'));
    }

    /**
     * Result count
     *
     * @return int|void
     */
    public function getCount(): int
    {
        return $this->getList()->count();
    }

    /**
     * Total count
     *
     * @return int
     */
    public function getTotal(): int
    {
        return $this->getIntegerAttributes('total');
    }
}
