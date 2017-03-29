<?php

namespace app\modules\admin;

use yii\filters\AccessControl;
use app\modules\admin\models\Dictionary;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $layout = 'admin';
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
                            return (! \Yii::$app->user->isGuest ) &&  (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers")));
                            //return ! \Yii::$app->user->isGuest;
                        }
                    ],
                ],
            ],
        ];
    }
}
