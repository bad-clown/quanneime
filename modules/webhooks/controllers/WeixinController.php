<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: WeixinController.php
 * $Id: WeixinController.php v 1.0 2015-11-24 22:36:24 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-28 23:05:18 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\webhooks\controllers;
use app\modules\admin\logic\DictionaryLogic;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\components\BaseController;
use app\components\Weixin;
use hipstercreative\user\models\User;
use app\modules\admin\models\AutoInc;
use yii\helpers\Url;

/**
 * WeixinController implements the webhooks of weixin.
 */
class WeixinController extends BaseController {
    public $enableCsrfValidation = false;

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * 微信获取code接口回调
     */
    public function actionCode( $state, $code = 0) {
        if ($code != 0) {
            $result = Weixin::loginCallback($code,$state);
            if ($result['errorCode'] == DictionaryLogic::getErrorCode('NeedInviteCode')) {
                $this->redirect(array('/m/index/invite-code', 'key' => $result['key']));
            }
            else {
                $this->redirect(Url::home());
            }
        }
    }

    /*public function actionTest(){
        $userInfo = json_decode('{ "openid": "OPENID", "nickname": "NICKNAME", "sex": 1, "province": "PROVINCE", "city": "CITY", "country": "COUNTRY", "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0", "privilege": [ "PRIVILEGE1", "PRIVILEGE2" ], "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL" }');
        $user  = new User(['scenario' => 'weixinconnect']);
        $user["username"] = "WX".$userInfo->nickname."_".AutoInc::next();
        $user["email"]  = $userInfo->unionid."@weixin.com";
        $user["registered_from"] = NOW;
        $user["confirmed_at"] = NOW;
        $user["created_at"] = NOW;
        $user["auth_key"] =Yii::$app->getSecurity()->generateRandomString() ;
        $user["password_hash"] =Yii::$app->getSecurity()->generatePasswordHash("weixin");
        $user["openid"] = $userInfo->openid;
        $user->save();
    }*/

}
