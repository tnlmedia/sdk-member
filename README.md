# MemberSDK Libery Package

## Maintener 
Ken(ken@sportsv.net) 

James(james.liu@thenewslens.com)

## Installation

### System Requirements

- PHP >= 7.0

### Composer Installation

```shell
php composer.phar require tnlmedia/member-sdk
```
or
```shell
composer require tnlmedia/member-sdk
```
### Usage

doesn't use laravel
```php
// include composer autoload
require 'vendor/autoload.php';

// import the Tnlmedia Member Manager Class
use Tnlmedia\MemberSDK\MemberSDK;

// config setting
$config = [
    'auth_uri'      => YOUR_TNLMEDIA_MEMBER_AUTH_URI,
    'api_uri'       => YOUR_TNLMEDIA_MEMBER_API_URI,
    'redirect_url'  => YOUR_TNLMEDIA_MEMBER_REDIRECT_URL,
    'client_id'     => YOUR_TNLMEDIA_MEMBER_CLIENT_ID,
    'client_secret' => YOUR_TNLMEDIA_MEMBER_CLIENT_SECRET,
];

// create an member manager instance 
$membersdk = new MemberSDK($config);

// auth url redirect 
$membersdk->redirect();
```

### Integration in Laravel

In the $providers array add the service providers for this package.
```php
Tnlmedia\MemberSDK\MemberSDKServiceProvider::class
```
Add the facade of this package to the $aliases array.
```php
'MemberSDK' => Tnlmedia\MemberSDK\Facades\MemberSDK::class
```

### Configuration

Publish configuration in Laravel
```shell
$ php artisan vendor:publish --provider="Tnlmedia\MemberSDK\MemberSDKServiceProviderLaravelRecent"
```
Publish configuration in Laravel <= 4
```shell
$ php artisan config:publish tnlmedia/member-sdk 
```

### APIs
1. MemberSDK::getAuthUrl()

1. MemberSDK::redirect()

1. MemberSDK::user()

1. MemberSDK::getAccessTokenByCerdentials()

1. MemberSDK::setToken(token)

1. MemberSDK::setScopes(scopes)

1. MemberSDK::getTokenStatus(token)

1. MemberSDK::getUserById(id)

1. MemberSDK::updateUserStatus(id, status)

1. MemberSDK::usersSearch(keyword, options = [])
