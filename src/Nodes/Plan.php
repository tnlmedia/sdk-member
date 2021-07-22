<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use TNLMedia\MemberSDK\Constants\PlanStatusConstants;

class Plan extends Node
{
    /**
     * Unique ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getIntegerAttributes('id');
    }

    /**
     * Plan display name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getStringAttributes('name');
    }

    /**
     * 91App product sku
     *
     * @return string
     */
    public function get91AppSku()
    {
        return $this->getStringAttributes('91app');
    }

    /**
     * Period type
     *
     * @return string
     */
    public function getPeriodType()
    {
        return $this->getStringAttributes('type');
    }

    /**
     * Period length
     *
     * @return int
     */
    public function getLength()
    {
        return $this->getIntegerAttributes('length');
    }

    /**
     * Sort weight
     *
     * @return int
     */
    public function getWeight()
    {
        return $this->getIntegerAttributes('weight');
    }

    /**
     * Product page link
     *
     * @return string
     */
    public function getAction()
    {
        return $this->getStringAttributes('action');
    }

    /**
     * Build time
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->getDateTimeAttributes('created');
    }

    /**
     * Total completed history
     *
     * @return int
     */
    public function countCompletedHistory()
    {
        return $this->getIntegerAttributes('counter.completed');
    }

    /**
     * Total failed history
     *
     * @return int
     */
    public function countFailedHistory()
    {
        return $this->getIntegerAttributes('counter.failed');
    }

    /**
     * Total cancelled history
     *
     * @return int
     */
    public function countCancelledHistory()
    {
        return $this->getIntegerAttributes('counter.cancelled');
    }

    /**
     * Total returned history
     *
     * @return int
     */
    public function countReturnedHistory()
    {
        return $this->getIntegerAttributes('counter.returned');
    }

    /**
     * Plan is available
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getIntegerAttributes('status') == PlanStatusConstants::ENABLED;
    }
}
