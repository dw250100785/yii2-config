yii2-config
===========

[![Build Status](https://img.shields.io/travis/weyii/yii2-config.svg?style=flat-square&branch=tests)](http://travis-ci.org/weyii/yii2-config)
[![version](https://img.shields.io/packagist/v/weyii/yii2-config.svg?style=flat-square)](https://packagist.org/packages/weyii/yii2-config)
[![Download](https://img.shields.io/packagist/dt/weyii/yii2-config.svg?style=flat-square)](https://packagist.org/packages/weyii/yii2-config)
[![codecov.io](https://img.shields.io/codecov/c/github/weyii/yii2-config.svg?style=flat-square)](https://codecov.io/github/weyii/yii2-config)

Thank you choice yii2-config, The config component for Yii2.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
componser require --prefer-dist weyii/yii2-config "*"
```

or add

```
"weyii/yii2-config": "*"
```

to the require section of your `composer.json` file.

After running 

```
composer update
```

run

```
yii migrate --migrationPath=@vendor/weyii/yii2-config/migrations
```

After that change your main configuration file ```config/web.php```

```php
<?php return [
    ...
    'components' => [
        ...
        'config' => [
            'class' => 'weyii\config\DbConfig',
        ],
        ...
    ],
    ...
];
```


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
Yii::$app->config->set('test.foo.bar', 'hello');
Yii::$app->config->set('test1.foo.bar', 'world');
Yii::$app->config->get('test.foo.bar', 'world');
Yii::$app->config->delete('test1.foo');
Yii::$app->config->delete('test.foo.bar');
print_r(Yii::$app->config->get());
```
