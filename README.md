[![Build Status](https://travis-ci.org/descom-es/php-lib.svg?branch=master)](https://travis-ci.org/descom-es/php-lib)


## Installation

You can install the package via composer:

```bash
composer require descom-es/php-lib
```

Publish package config file:
```bash
php artisan vendor:publish
```
(Select Provider: DescomLib\DescomLibServiceProvider)

Insert token in config/descom_lib.php


### Testing

``` bash
composer test
```


### Data

Data example for $notificationManager->send($data);

```bash
[
    'action' => 'loggedEmail',
    'data'   => [
        'email'        => 'test@descom.es',
        'subscription' => 'descom.es',
        'ip'           => '54.194.66.61',
        'geo'          => [
            'country' => [
                'name' => 'España',
                'iso'  => 'ES'
            ]
        ],
        'instance'=> [
            'hostname' => 'dcaw-019.test.es',
            'name'     => 'DC/TEST/Linux/01',
        ],
        'dns'  => [
            'hostname'=> 'hostname.test.com',
            'domain'  => 'test.com',
        ]
    ]
]
```
