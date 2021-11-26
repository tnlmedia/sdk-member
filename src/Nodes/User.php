<?php

namespace TNLMedia\MemberSDK\Nodes;

use DateTime;
use Throwable;
use TNLMedia\MemberSDK\MemberSDK;

class User extends Node
{
    /**
     * Require loaded
     *
     * @var array
     */
    protected $requires = [];

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
        return strval(explode('-', $this->getStringAttributes('mobile.telcode'))[0] ?? '');
    }

    /**
     * Contact mobile country code in ISO 3166-1 alpha-3
     *
     * @return string
     */
    public function getMobileISO()
    {
        return strval(explode('-', $this->getStringAttributes('mobile.telcode'))[1] ?? '');
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
        $this->requireDetail();
        return $this->getStringAttributes('realname');
    }

    /**
     * Birthday
     *
     * @return DateTime
     */
    public function getBirthday()
    {
        $this->requireDetail();

        $value = new DateTime();
        $value->setTime(0, 0, 0);
        try {
            [$year, $month, $day] = explode('-', $this->getStringAttributes('birthday'));
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
        $this->requireDetail();
        return $this->getStringAttributes('gender');
    }

    /**
     * Country key
     *
     * @return string
     */
    public function getCountry()
    {
        $this->requireDetail();
        return $this->getStringAttributes('country');
    }

    /**
     * Education level key
     *
     * @return string
     */
    public function getEducation()
    {
        $this->requireDetail();
        return $this->getStringAttributes('education');
    }

    /**
     * Occupation type key
     *
     * @return string
     */
    public function getOccupation()
    {
        $this->requireDetail();
        return $this->getStringAttributes('occupation');
    }

    /**
     * Headline key
     *
     * @return string
     */
    public function getHeadline()
    {
        $this->requireDetail();
        return $this->getStringAttributes('headline');
    }

    /**
     * Income range key
     *
     * @return string
     */
    public function getIncome()
    {
        $this->requireDetail();
        return $this->getStringAttributes('income');
    }

    /**
     * Relationship status key
     *
     * @return string
     */
    public function getRelationship()
    {
        $this->requireDetail();
        return $this->getStringAttributes('relationship');
    }

    /**
     * Google connect ID
     *
     * @return string
     */
    public function getGoogleID()
    {
        $this->requireDetail();
        return $this->getStringAttributes('connect.google.id');
    }

    /**
     * Facebook connect ID
     *
     * @return string
     */
    public function getFacebookID()
    {
        $this->requireDetail();
        return $this->getStringAttributes('connect.facebook.id');
    }

    /**
     * Get service ID list
     *
     * @return array
     */
    public function getServiceList()
    {
        $this->requireDetail();
        return array_column($this->getArrayAttributes('service'), 'id');
    }

    /**
     * Get service slug list
     *
     * @return array
     */
    public function getServiceSlugList()
    {
        $this->requireDetail();
        return array_column($this->getArrayAttributes('service'), 'slug');
    }

    /**
     * Get certificate ID list
     *
     * @return array
     */
    public function getCertificateList()
    {
        $this->requireDetail();
        return array_column($this->getArrayAttributes('certificate'), 'id');
    }

    /**
     * Get certificate slug list
     *
     * @return array
     */
    public function getCertificateSlugList()
    {
        $this->requireDetail();
        return array_column($this->getArrayAttributes('certificate'), 'slug');
    }

    /**
     * Get flag name list
     *
     * @return array
     */
    public function getFlagList()
    {
        $this->requireDetail();
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
     * User is enabled
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
        $this->requireDetail();
        return $this->getBooleanAttributes('privacy.dmp', true);
    }

    /**
     * Member has service
     *
     * @param $value
     * @return bool
     */
    public function hasService($value)
    {
        if (in_array($value, $this->getServiceList())) {
            return true;
        }
        if (in_array($value, $this->getServiceSlugList())) {
            return true;
        }
        return false;
    }

    /**
     * Member authorized certificate
     *
     * @param $value
     * @return bool
     */
    public function hasCertificate($value)
    {
        if (in_array($value, $this->getCertificateList())) {
            return true;
        }
        if (in_array($value, $this->getCertificateSlugList())) {
            return true;
        }
        return false;
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

    /**
     * Load user detail
     */
    protected function requireDetail()
    {
        // Check available
        if (!$this->core instanceof MemberSDK) {
            return;
        }
        if (array_key_exists('created', $this->attributes)) {
            return;
        }
        if (array_key_exists('detail', $this->requires)) {
            return;
        }
        $this->requires['detail'] = true;

        // Request
        try {
            $result = $this->core->user->get($this->getId());
        } catch (Throwable $e) {
            return;
        }

        // Update
        $this->initial($result->getArrayAttributes() + $this->attributes);
    }
}
