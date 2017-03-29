<?php

$params = require(__DIR__ . '/params.php');

define('NOW', time());

$config = [
    'id' => '圈内觅',
    'name' => '圈内觅',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=> $m ? "m/site/index" : "site/index",
    //'catchAll' => ['site/offline'],
    'language'=>'zh-CN',
    'components' => [
        'assetManager' => [
            'assetMap' => [
                'jquery.js' => '/js/jquery.1.11.1.min.js',
            ],
            ],
        /*'errorHandler' => [
            'errorAction' => '/site/error',
        ],*/
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '<~/.<_(d$1:>e=',
        ],
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
        'user' => [
            //'identityClass' => 'app\modules\admin\models\User',
            'identityClass' => 'hipstercreative\user\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        /*'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            ],*/
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
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'categories' => ['application'],
                    'logFile' => '@app/runtime/logs/application.log',
                ],
            ],
        ],
        'formatter'=>[
            'class'=>'yii\i18n\Formatter',
            'dateFormat'=> 'yyyy-MM-dd',
            'datetimeFormat'=>'php:Y-m-d H:i:s',
            'timeFormat'=>'php:H:i:s',
        ],
        'db' => require(__DIR__ . '/db.php'),
        'mongodb' => require(__DIR__ . '/mongodb.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

$config['bootstrap'][] = 'admin';
$config['modules']['admin'] = 'app\modules\admin\Module';

$config['bootstrap'][] = 'common';
$config['modules']['common'] = 'app\modules\common\Module';

$config['bootstrap'][] = 'user';
$config['modules']['user'] = 'hipstercreative\user\Module';

$config['bootstrap'][] = 'm';
$config['modules']['m'] = 'app\modules\m\Module';

$config['bootstrap'][] = 'image';
$config['modules']['image'] = 'app\modules\image\Module';

return $config;
