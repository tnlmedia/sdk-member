<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use TNLMedia\MemberSDK\Constants\ServiceStatusConstants;

class Service extends Node
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
     * Service unique key in console
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getStringAttributes('slug');
    }

    /**
     * Service display name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getStringAttributes('name');
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
     * Build time
     *
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->getDateTimeAttributes('created');
    }

    /**
     * Total subscription user
     *
     * @return int
     */
    public function countUser()
    {
        return $this->getIntegerAttributes('counter.users');
    }

    /**
     * Total completed history
     *
     * @return int
     * @deprecated Since 3.0
     */
    public function countCompletedHistory()
    {
        return $this->countCompletedTransaction();
    }

    /**
     * Total completed transaction
     *
     * @return int
     */
    public function countCompletedTransaction()
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
        return $this->countFailedTransaction();
    }

    /**
     * Total failed transaction
     *
     * @return int
     */
    public function countFailedTransaction()
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
        return $this->countCancelledTransaction();
    }

    /**
     * Total cancelled transaction
     *
     * @return int
     */
    public function countCancelledTransaction()
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
        return $this->countReturnedTransaction();
    }

    /**
     * Total returned transaction
     *
     * @return int
     */
    public function countReturnedTransaction()
    {
        return $this->getIntegerAttributes('counter.returned');
    }

    /**
     * Service is available
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getIntegerAttributes('status') == ServiceStatusConstants::ENABLED;
    }
}
