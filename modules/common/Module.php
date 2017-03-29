<?php

namespace app\modules\common;

use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\common\controllers';
    public $layout = 'common';
    public $defaultRoute ="index";

    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            //return (! \Yii::$app->user->isGuest ) ;
                            return true;
                        }
                    ],
                ],
            ],
        ];
    }
}
