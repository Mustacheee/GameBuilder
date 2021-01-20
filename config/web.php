<?php

use app\common\components\JWTAuthTokenGenerator;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'layout' => null,
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'qTWgUHkcWVaqqvUAin2Vwv5gICUFe5Zg',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'login' => 'login/login',
                'signup' => 'site/signup',
                'contact-us' => 'site/contact-us',
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => ['user']
                ],
                '<controller>/<action>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'jwt' => [
            'class' => 'sizeg\jwt\Jwt',
            'key' => 'secret', #TODO:: Change this
        ],
        'accessTokenGenerator' => [
            'class' => JWTAuthTokenGenerator::class
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
}

return $config;
