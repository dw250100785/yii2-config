<?php
define('YII_DEBUG', true);
define('YII_ENV', 'dev');
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

new \yii\web\Application([
    'id' => 'test',
    'name' => 'unit',
    'basePath' => __DIR__,
]);
