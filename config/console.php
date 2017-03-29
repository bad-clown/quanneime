<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

define('NOW', time());
define('WEBROOT', dirname(__DIR__));

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'gii', 'admin', 'web', 'm'],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'admin' => 'app\modules\admin\Module',
        'web' => 'app\modules\web\Module',
        'm' => 'app\modules\m\Module',
    ],
    'components' => [
        'cache' => [
            //'class' => 'yii\caching\FileCache',
            'class' => 'yii\caching\MemCache',
            'useMemcached' => true,
            'servers' => [
                [
                    'host'=>"localhost",
                    'port' => 11211,
                ],
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.ym.163.com',
                'username' => 'quannei@quannei.me',
                'password' => 'GOODjob88',
                'port' => '25',
              ],
          ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'yii*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/vendor/yiisoft/yii2/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
        'db' => $db,
        'mongodb' => require(__DIR__ . '/mongodb.php'),
    ],
    'params' => $params,
];
