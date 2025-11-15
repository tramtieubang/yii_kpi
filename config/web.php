<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

Yii::setAlias('@webvimark', dirname(__DIR__) . '/vendor/webvimark/module-user-management');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language'=>'vi',
    'timeZone' => 'Asia/Ho_Chi_Minh',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'modules'=>[
        
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],  
        'user-management' => [
            'class' => 'webvimark\modules\UserManagement\UserManagementModule',
            // 'enableRegistration' => true,

            // Add regexp validation to passwords. Default pattern does not restrict user and can enter any set of characters.
            // The example below allows user to enter :
            // any set of characters
            // (?=\S{8,}): of at least length 8
            // (?=\S*[a-z]): containing at least one lowercase letter
            // (?=\S*[A-Z]): and at least one uppercase letter
            // (?=\S*[\d]): and at least one number
            // $: anchored to the end of the string

            //'passwordRegexp' => '^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$',
            // Here you can set your handler to change layout for any controller or action
            // Tip: you can use this event in any module
            'on beforeAction'=>function(yii\base\ActionEvent $event) {
                if ( $event->action->uniqueId == 'user-management/auth/login' )
                {
                    $event->action->controller->layout = 'loginLayout.php';
                };
            },
        ],
        
    ],

    'components' => [
        'i18n' => [
            'translations' => [
                'user-management*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@vendor/webvimark/module-user-management/messages',
                    'sourceLanguage' => 'vi',
                    'fileMap' => [
                        'user-management' => 'user-management.php',
                    ],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '634a84aa5fd9b4eae0896e22b3b3c7fd6fce22665530559ed231e566f4c97d25',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'user' => [
            //'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 3600, 
        ],
        
        'user' => [
            'class' => 'webvimark\modules\UserManagement\components\UserConfig',
            //'class' => 'app\modules\user\components\UserConfig',
            //Super@dminNTTV.VN

            // Comment this if you don't want to record user logins
            'on afterLogin' => function($event) {
                \webvimark\modules\UserManagement\models\UserVisitLog::newVisitor($event->identity->id);
            }
        ],

        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		
		// Thêm assetManager ở đây
        'assetManager' => [
            //'baseUrl' => 'https://yii-ttb.local', // ép assets HTTPS
            'bundles' => [
                'yii\bootstrap\BootstrapAsset' => [
                    'class' => \yii\bootstrap5\BootstrapAsset::class,
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'class' => \yii\bootstrap5\BootstrapPluginAsset::class,
                ],
            ],
        ],
		       
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@app/views' => '@app/themes/main',
                ],
                'baseUrl' => '@web/../themes/main',
            ],
        ],
		
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // send all mails to a file by default.
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
                //'/' => '/home',
                '/' => 'site/index', // site/index sẽ tự kiểm tra login hay chưa
                '<controller>/<action>' => '<controller>/<action>',
                //'POST api/user/login' => 'api/user/login',
            ],
        ],
        
    ],
   
    'params' => $params,
    //'defaultRoute' => 'home/default', // controller/home + action/default
];

$config['modules'] =  array_merge($config['modules'], require __DIR__ . '/modules.php');
// hoặc
/*
    'modules' => array_merge(
        require __DIR__ . '/modules.php',
        [
            'gridview' => [
                'class' => '\kartik\grid\Module',
            ],
        ]
    ),
*/


if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    //$config['bootstrap'][] = 'debug';
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
