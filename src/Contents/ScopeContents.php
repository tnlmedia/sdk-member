<?php

namespace TNLMedia\MemberSDK\Contents;

/**
 * Class ScopeContents
 * @package TNLMedia\MemberSDK\Contents
 * @see https://member.tnlmedia.com/docs/#/v1/auth/scope
 */
class ScopeContents
{
    // Client
    const CONSOLE_SWITCH = 'console_switch';
    const IMPLICIT_TOKEN = 'implicit_token';

    // User
    const USER_BASIC = 'user_basic';
    const USER_PROFILE = 'user_profile';
    const USER_CONNECT = 'user_connect';
    const USER_STATUS = 'user_status';

    // Service
    const SERVICE_ACCESS = 'service_access';
    const SERVICE_MODIFY = 'service_modify';
    const SERVICE_USER = 'service_user';

    // Flag
    const FLAG_ACCESS = 'flag_access';
    const FLAG_USER = 'flag_user';

    // Contact
    const CONTACT_ALERT = 'contact_alert';
    const CONTACT_NOTIFY = 'contact_notify';
    const CONTACT_REPORT = 'contact_report';
    const CONTACT_EDM = 'contact_edm';
}
