<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Module.php
 * $Id: Module.php v 1.0 2015-11-03 22:52:08 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 22:52:52 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\image;

use yii\filters\AccessControl;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\image\controllers';

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
