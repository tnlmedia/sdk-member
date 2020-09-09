# Member Oauth Libery Package

## Maintener

Ken (ken@sportsv.net)
James (james.liu@thenewslens.com)

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
use Tnlmedia\MemberSDK\Member;

// config setting
$config = [
    'auth_uri'      => YOUR_TNLMEDIA_MEMBER_AUTH_URI,
    'api_uri'       => YOUR_TNLMEDIA_MEMBER_API_URI,
    'redirect_url'  => YOUR_TNLMEDIA_MEMBER_REDIRECT_URL,
    'client_id'     => YOUR_TNLMEDIA_MEMBER_CLIENT_ID,
    'client_secret' => YOUR_TNLMEDIA_MEMBER_CLIENT_SECRET,
];

// create an member manager instance 
$member = new Member($config);

// auth url redirect 
$member->redirect();
```

### Integration in Laravel

In the $providers array add the service providers for this package.
```php
Tnlmedia\MemberSDK\MemberServiceProvider::class
```
Add the facade of this package to the $aliases array.
```php
'Member' => Tnlmedia\MemberSDK\Facades\Member::class
```

### Configuration

Publish configuration in Laravel
```shell
$ php artisan vendor:publish --provider="Tnlmedia\MemberSDK\MemberServiceProviderLaravelRecent"
```
Publish configuration in Laravel <= 4
```shell
$ php artisan config:publish tnlmedia/member-sdk 
```

### APIs
1. Member::getAuthUrl()

1. Member::redirect()

1. Member::user()

1. Member::getAccessTokenByCerdentials()

1. Member::setToken(token)

1. Member::setScopes(scopes)

1. Member::getTokenStatus(token)

1. Member::getUserById(id)

1. Member::updateUserStatus(id, status)

1. Member::usersSearch(keyword, options = [])
