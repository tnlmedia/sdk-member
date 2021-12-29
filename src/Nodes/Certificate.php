<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use TNLMedia\MemberSDK\Constants\CertificateStatusConstants;

class Certificate extends Node
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
     * Unique key in console
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->getStringAttributes('slug');
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
     * Display name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getStringAttributes('name');
    }

    /**
     * Display description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->getStringAttributes('description');
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
     */
    public function getProductUrl()
    {
        return $this->getStringAttributes('product');
    }

    /**
     * Purchase url
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
     * Total certificate owner
     *
     * @return int
     */
    public function countUser()
    {
        return $this->getIntegerAttributes('counter.users');
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
     * Total failed transaction
     *
     * @return int
     */
    public function countFailedTransaction()
    {
        return $this->getIntegerAttributes('counter.failed');
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
     * Total returned transaction
     *
     * @return int
     */
    public function countReturnedTransaction()
    {
        return $this->getIntegerAttributes('counter.returned');
    }

    /**
     * Certificate is available
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getIntegerAttributes('status') == CertificateStatusConstants::ENABLED;
    }
}
