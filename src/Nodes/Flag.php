<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use TNLMedia\MemberSDK\Constants\FlagTypeConstants;

class Flag extends Node
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
     * Flag text
     *
     * @return string
     */
    public function getName()
    {
        return $this->getStringAttributes('name');
    }

    /**
     * Generate type
     *
     * @return string
     */
    public function getType()
    {
        return $this->getStringAttributes('type');
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
     * Total assigned user
     *
     * @return int
     */
    public function countUser()
    {
        return $this->getIntegerAttributes('count.users');
    }

    /**
     * Build from client
     *
     * @return bool
     */
    public function isCustom()
    {
        return $this->getType() == FlagTypeConstants::CUSTOM;
    }

    /**
     * Build from client access
     *
     * @return bool
     */
    public function isClient()
    {
        return $this->getType() == FlagTypeConstants::CLIENT;
    }
}
