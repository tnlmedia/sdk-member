<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use Throwable;

class User extends Node
{
    /**
     * Member ID
     *
     * @return int
     */
    public function getId()
    {
        return $this->getIntegerAttributes('id');
    }

    /**
     * Display name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getStringAttributes('nickname');
    }

    /**
     * Avatar image
     *
     * @return string
     */
    public function getAvatar()
    {
        return $this->getStringAttributes('avatar');
    }

    /**
     * Contact mail
     *
     * @return string
     */
    public function getMail()
    {
        return $this->getStringAttributes('mail.value');
    }

    /**
     * Contact mobile country code in E.164
     *
     * @return string
     */
    public function getMobileCode()
    {
        return strval(explode('-', $this->getStringAttributes('mobile.tnlcode'))[0] ?? '');
    }

    /**
     * Contact mobile country code in ISO 3166-1
     *
     * @return string
     */
    public function getMobileISO()
    {
        return strval(explode('-', $this->getStringAttributes('mobile.tnlcode'))[1] ?? '');
    }

    /**
     * Contact mobile
     *
     * @return string
     */
    public function getMobile()
    {
        $value = $this->getStringAttributes('mobile.value');
        if (!empty($value)) {
            $value = $this->getMobileCode() . $value;
            $value = '+' . $value;
        }
        return $value;
    }

    /**
     * Preferred language in IETF
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->getStringAttributes('language');
    }

    /**
     * Preferred timezone
     *
     * @return string
     */
    public function getTimezone()
    {
        return $this->getStringAttributes('timezone');
    }

    /**
     * Preferred currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->getStringAttributes('currency');
    }

    /**
     * Member real name
     *
     * @return string
     */
    public function getRealname()
    {
        return $this->getStringAttributes('realname');
    }

    /**
     * Birthday
     *
     * @return DateTime
     */
    public function getBirthday()
    {
        $value = new DateTime();
        $value->setTime(0, 0, 0);
        try {
            list($year, $month, $day) = explode('-', $this->getStringAttributes('birthday'));
            $value->setDate(intval($year), intval($month), intval($day));
        } catch (Throwable $e) {
        }
        return $value;
    }

    /**
     * Gender key
     *
     * @return string
     */
    public function getGender()
    {
        return $this->getStringAttributes('gender');
    }

    /**
     * Country key
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->getStringAttributes('country');
    }

    /**
     * Education level key
     *
     * @return string
     */
    public function getEducation()
    {
        return $this->getStringAttributes('education');
    }

    /**
     * Occupation type key
     *
     * @return string
     */
    public function getOccupation()
    {
        return $this->getStringAttributes('occupation');
    }

    /**
     * Headline key
     *
     * @return string
     */
    public function getHeadline()
    {
        return $this->getStringAttributes('headline');
    }

    /**
     * Income range key
     *
     * @return string
     */
    public function getIncome()
    {
        return $this->getStringAttributes('income');
    }

    /**
     * Relationship status key
     *
     * @return string
     */
    public function getrelationship()
    {
        return $this->getStringAttributes('relationship');
    }

    /**
     * Google connect ID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->getStringAttributes('connect.google.id');
    }

    /**
     * Facebook connect ID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->getStringAttributes('connect.facebook.id');
    }

    /**
     * Get service ID list
     *
     * @return array
     */
    public function getServiceList()
    {
        return array_column($this->getArrayAttributes('service'), 'id');
    }

    /**
     * Get flag name list
     *
     * @return array
     */
    public function getFlagList()
    {
        return array_column($this->getArrayAttributes('flag'), 'name');
    }

    /**
     * Contact mail is verified
     *
     * @return bool
     */
    public function isMailVerify()
    {
        return $this->getBooleanAttributes('mail.verify');
    }

    /**
     * Contact mobile is verified
     *
     * @return bool
     */
    public function isMobileVerify()
    {
        return $this->getBooleanAttributes('mobile.verify');
    }

    /**
     * Member is enabled
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->getBooleanAttributes('status');
    }

    /**
     * DMP tracking enabled
     *
     * @return bool
     */
    public function isDmpEnable()
    {
        return $this->getBooleanAttributes('privacy.dmp', true);
    }

    /**
     * Member has service
     *
     * @param int $id
     * @return bool
     */
    public function hasService(int $id)
    {
        return in_array($id, $this->getServiceList());
    }

    /**
     * Member has flag
     *
     * @param string $name
     * @return bool
     */
    public function hasFlag(string $name)
    {
        return in_array($name, $this->getFlagList());
    }
}
