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
     * Unique slug in console
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getStringAttributes('slug');
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
     * Price currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getStringAttributes('currency');
    }

    /**
     * Price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->getFloatAttributes('price');
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
     * Product page url
     *
     * @return string
     * @deprecated Since 3.0
     */
    public function getAction()
    {
        return $this->getActionUrl();
    }

    /**
     * Product page url
     *
     * @return string
     */
    public function getActionUrl()
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
     * @deprecated Since 3.0
     */
    public function countCompletedHistory()
    {
        return $this->getIntegerAttributes('counter.completed');
    }

    /**
     * Total failed history
     *
     * @return int
     * @deprecated Since 3.0
     */
    public function countFailedHistory()
    {
        return $this->getIntegerAttributes('counter.failed');
    }

    /**
     * Total cancelled history
     *
     * @return int
     * @deprecated Since 3.0
     */
    public function countCancelledHistory()
    {
        return $this->getIntegerAttributes('counter.cancelled');
    }

    /**
     * Total returned history
     *
     * @return int
     * @deprecated Since 3.0
     */
    public function countReturnedHistory()
    {
        return $this->getIntegerAttributes('counter.returned');
    }

    /**
     * Plan can be recurring
     *
     * @return bool
     */
    public function isRecurring()
    {
        return $this->getBooleanAttributes('recurring');
    }

    /**
     * Plan is visible on subscription page
     *
     * @return bool
     */
    public function isVisible()
    {
        return $this->getBooleanAttributes('visible');
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
