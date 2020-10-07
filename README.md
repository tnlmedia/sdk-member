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

### Usage By Laravel

#### Use By Cert Token
```php
// Use Cert Token
$membersdk = MemberSDK::initAccess();
```

#### Use By Auth Login Token
```php
use MemberSDK;

// location auth redirect 
MemberSDK::redirect(); 

// get callback code 
$membersdk = MemberSDK::callback();
```



### Integration Without Laravel

#### Init
```php
// include composer autoload
require 'vendor/autoload.php';

// import the Tnlmedia Member Manager Class
use Tnlmedia\MemberSDK\MemberSDK;

// config setting
$config = [
    'client_id'     => TNLMEDIA_MEMBER_CLIENT_ID,
    'client_secret' => TNLMEDIA_MEMBER_CLIENT_SECRET,
    'redirect_url'  => TNLMEDIA_MEMBER_REDIRECT_URL,
];
```
#### Use By Cert Token
```php
// create an member sdk instance 
$membersdk = new MemberSDK($config);

// Use Cert Token
$membersdk = $membersdk->initAccess();
```

#### Use By Auth Login Token
```php
// create an member sdk instance 
$membersdk = new MemberSDK($config);

// location redirect auth url
$membersdk->redirect(); 

// get user by auth login callback code 
$membersdk = membersdk->callback();

// get current user
$me = $membersdk->getMe();

```

### Environment

```php
// Production
$membersdk->onProduction();

// Stage
$membersdk->onStage();
```

### Debug
```php
// Enable
$membersdk->enableDebug();

// Disable
$membersdk->disableDebug();
```




### APIs
```php
// get auth url
$membersdk->getAuthUrl()

// location auth redirect
$membersdk->redirect()

// get callback code
$membersdk->callback()

// get current user
$membersdk->getMe();

// get current user from token
$membersdk->getMeFromToken(token);

// set token
$membersdk->setToken(token)

// set scopes
$membersdk->setScopes(scopes)

// get token status
$membersdk->getTokenStatus(token)

// get user by user id
$membersdk->getUserById(id)

// update user status
$membersdk->updateUserStatus(id, status)

// search user
$membersdk->usersSearch(keyword, options = [])
```
