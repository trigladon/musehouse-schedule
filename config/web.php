<?php

$dotenv = new \Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();
$dotenv->required(['EMAIL', 'EMAIL_PASS', 'DB_NAME', 'DB_PASS', 'DB_USER']);

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'name' => 'MuseHouse Schedule',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'master' => [
            'class' => 'app\modules\master\Module',
            'layout' => 'main',
            'defaultRoute' => 'calendar/index',
        ],
        'teacher' => [
            'class' => 'app\modules\teacher\Module',
            'layout' => 'main',
            'defaultRoute' => 'calendar/index',
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'nSWkSG8bsKEKmZpLUECvHxc_2QiNzuB7',
            'baseUrl' => '',
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
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.gmail.com',
                'username' => getenv('EMAIL'),
                'password' => getenv('EMAIL_PASS'),
                'port' => '587',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
            ],
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                'index' => 'site/index',
                'login' => 'site/login',
                'registration' => 'site/registration',
                'calendar' => 'teacher/calendar',
                'statistics' => 'teacher/statistics',
                'lessons' => 'master/instrument',
                'users' => 'master/users',
                'profile' => 'teacher/profile',
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
//        'formatter' => [
//            'class' => 'yii\i18n\Formatter',
//            'defaultTimeZone' => 'Europe/Minsk',
//            'timeZone' => 'GMT+3',
//        ],

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
