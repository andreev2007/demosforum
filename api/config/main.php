<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'controllerNamespace' => 'api\controllers',
    'components' => [
        'request' => [
           'baseUrl' => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ],
            'enableCookieValidation' => false,
            'enableCsrfValidation' => false,
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => [
                    'class' => 'yii\web\JsonResponseFormatter',
                    'prettyPrint' => YII_DEBUG,
                    'encodeOptions' => JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
                ]
            ]
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'POST login' => 'site/login',
                'POST signup' => 'site/sign-up',
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'posts',
                    'extraPatterns' => [
                        'POST {id}/like' => 'like',
                        'DELETE {id}/un-like' => 'un-like',
                        'POST {id}/star' => 'star',
                        'DELETE {id}/un-star' => 'un-star',
                        'GET {id}/view' => 'view',
                        'PUT {id}/comment-update' => 'comment-update',
                        'DELETE {id}/comment-delete' => 'comment-delete',
                        'PUT {id}/delete' => 'delete',
                        'PUT {id}/update' => 'update',
                        'POST {id}/share' => 'share',
                        'POST {id}/create-comment' => 'create-comment',
                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'pluralize' => false,
                    'controller' => 'profile',
                    'extraPatterns' => [
                        'PUT {id}/update' => 'update',
                        'POST {id}/subscribe' => 'subscribe',
                        'DELETE {id}/un-subscribe' => 'un-subscribe',
                        'GET {id}/view' => 'view',
                        'GET subscribers' => 'subscribers',
                        'GET subscribed' => 'subscribed',
                        'GET commented' => 'commented',
                        'GET starred' => 'starred',
                        'GET settings' => 'settings',
                        'GET liked' => 'liked',
                        'GET view' => 'view',
                    ]
                ]
            ],
        ],
    ],
    'params' => $params,
];
