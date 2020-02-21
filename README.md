[![Build Status](https://travis-ci.org/descom-es/php-lib.svg?branch=master)](https://travis-ci.org/descom-es/php-lib)


# Installation

You can install the package via composer:

```bash
composer require descom/php-lib
```

Publish package config file:
```bash
php artisan vendor:publish --provider="DescomLib\DescomLibServiceProvider" --tag=config
```
- Select Provider: DescomLib\DescomLibServiceProvider

Insert token in config/descom_lib.php


# Notificacation Manager Serviice

With method `send`, you can send a request to service.

Thie method require `$data` argument. See samples

## Events

* DescomLib\Services\NotificationManager\Events\NotificationFailed

## Samples Data

```bash
[
    'action' => 'loggedEmail',
    'data'   => [
        'email'        => 'test@example.com',
        'subscription' => 'example.com',
        'ip'           => '192.168.0.1',
        'geo'          => [
            'country' => [
                'name' => 'España',
                'iso'  => 'ES'
            ]
        ],
        'instance'=> [
            'hostname' => 'hostname',
            'name'     => 'DC/TEST/Linux/01',
        ],
        'dns'  => [
            'hostname'=> 'hostname.test.com',
            'domain'  => 'test.com',
        ]
    ]
]
```

```bash
[
    'action' => 'loggedEmailWithoutSSL',
    'data'   => [
        'email'        => 'test@example.com',
        'subscription' => 'example.com',
    ],
]
```

# Testing

``` bash
composer test
```
