<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Weixin.php
 * $Id: Weixin.php v 1.0 2015-11-24 20:59:43 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-28 23:03:57 $
 * @brief
 *
 ******************************************************************/

namespace app\components;

use Yii;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\models\Dictionary;
use hipstercreative\user\models\User;
use app\modules\admin\models\AutoInc;

class Weixin extends \Yii\base\Object {

    /**
     * 二维码登陆接口的获取code接口uri.
     */
 static function makeFetchCodeUriQrcode($redirect_uri, $scope = 'snsapi_login', $state = 0) {
        $uri = DictionaryLogic::indexKeyValue('WeixinApi', 'FetchCodeUriQrcode', false);
        $search = array('%APPID%', '%REDIRECT_URI%', '%SCOPE%', '%STATE%');
        //$replace = array(DictionaryLogic::indexKeyValue('Weixin', 'AppId', false), $redirect_uri, $scope, $state);
        $replace = array(DictionaryLogic::indexKeyValue('Weixin', 'AppId', false), 'http://www.ibangbuy.com/', $scope, $state);
        $uri = str_replace($search, $replace, $uri);
        return $uri;
    }

    /**
     * oauth2接口的获取code接口uri.
     */
    public static function makeFetchCodeUriOauth2($redirect_uri, $scope = 'snsapi_userinfo', $state = 0) {
        //$uri = DictionaryLogic::indexKeyValue('WeixinApi', 'FetchCodeUriOauth2', false);
        $uri = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%APPID%&redirect_uri=%REDIRECT_URI%&response_type=code&scope=%SCOPE%&state=%STATE%#wechat_redirect';
        //Yii::error('uri 2:' . $uri, 'application');
        $search = array('%APPID%', '%REDIRECT_URI%', '%SCOPE%', '%STATE%');
        //$replace = array(DictionaryLogic::indexKeyValue('Weixin', 'AppId', false), $redirect_uri, $scope, $state);
        $replace = array(DictionaryLogic::indexKeyValue('Weixin', 'AppId', false),urlencode($redirect_uri), $scope, $state);
        $uri = str_replace($search, $replace, $uri);
        Yii::error('uri:' . $uri, 'application');
        return $uri;
    }

    /**
     * 获取微信access token
     * 返回值：
     * {
     *     "access_token":"ACCESS_TOKEN",
     *     "expires_in":7200,
     *     "refresh_token":"REFRESH_TOKEN",
     *     "openid":"OPENID",
     *     "scope":"SCOPE",
     *     "unionid": "o6_bmasdasdsad6_2sgVt7hMZOPfL"
     * }
     */
    public static function fetchAccessToken($code) {
        //$uri = DictionaryLogic::indexKeyValue('WeixinApi', 'FetchAccessToken', false);
        $uri = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=%APPID%&secret=%SECRET%&code=%CODE%&grant_type=authorization_code";
        $search = array('%APPID%', '%SECRET%', '%CODE%');
        $replace = array(
            DictionaryLogic::indexKeyValue('Weixin', 'AppId', false),
            DictionaryLogic::indexKeyValue('Weixin', 'AppSecret', false),
            $code);
        $uri = str_replace($search, $replace, $uri);
        $content = @file_get_contents($uri);
        return json_decode($content);
    }

    /**
     * 刷新微信access token, 当access token没有过期刷新时，access token不变，有效期延长。当access token已经过期再刷新时，返回的access token已经不是原来那个.
     * 返回值：
     * {
     *     "access_token":"ACCESS_TOKEN",
     *     "expires_in":7200,
     *     "refresh_token":"REFRESH_TOKEN",
     *     "openid":"OPENID",
     *     "scope":"SCOPE"
     * }
     */
    public static function refreshAccessToken($refreshToken) {
        $uri = DictionaryLogic::indexKeyValue('WeixinApi', 'RefreshAccessToken', false);
        $search = array('%APPID%', '%REFRESH_TOKEN%');
        $replace = array(DictionaryLogic::indexKeyValue('Weixin', 'AppId', false), $refreshToken);
        $uri = str_replace($search, $replace, $uri);
        $content = @file_get_contents($uri);
        return json_decode($content);
    }

    /** 获取用户信息接口
     * 返回值：
     * {
     *     "openid":"OPENID",
     *     "nickname":"NICKNAME",
     *     "sex":1,
     *     "province":"PROVINCE",
     *     "city":"CITY",
     *     "country":"COUNTRY",
     *     "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
     *     "privilege":[
     *         "PRIVILEGE1", 
     *         "PRIVILEGE2"
     *     ],
     *     "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL"
     * }
     */
    public static function fetchUserInfo($accessToken, $openId) {
        $uri = DictionaryLogic::indexKeyValue('WeixinApi', 'FetchUserInfo', false);
        $search = array('%ACCESS_TOKEN%', '%OPENID%');
        $replace = array($accessToken, $openId);
        $uri = str_replace($search, $replace, $uri);
        $content = @file_get_contents($uri);
        return json_decode($content);
    }


    public static function loginCallback($code,$state){
        $acctoken = self::fetchAccessToken($code);
        $openid = $acctoken->openid;
        $user = User::find()->where(['openid' => $openid])->one();
        if ($user == null) {
            $userInfo = self::fetchUserInfo($acctoken->access_token, $openid);
            //$userInfo = json_decode('{ "openid": "OPENID", "nickname": "NICKNAME", "sex": 1, "province": "PROVINCE", "city": "CITY", "country": "COUNTRY", "headimgurl": "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0", "privilege": [ "PRIVILEGE1", "PRIVILEGE2" ], "unionid": " o6_bmasdasdsad6_2sgVt7hMZOPfL" }');
            $user  = new User(['scenario' => 'weixinconnect']);
            $user["username"] = $userInfo->nickname . '_' . AutoInc::next();
            $user["email"]  = $userInfo->nickname."@weixin.com";
            $user["registered_from"] = NOW;
            $user["confirmed_at"] = NOW;
            $user["created_at"] = NOW;
            $user["auth_key"] =\Yii::$app->getSecurity()->generateRandomString() ;
            $user["password_hash"] =\Yii::$app->getSecurity()->generatePasswordHash("weixin");
            $user["openid"] = $userInfo->openid;
            $key = (string)new \MongoId();
            Cache::set($key, $user, 10 * 60);
            return array(
                'errorCode' => DictionaryLogic::getErrorCode('NeedInviteCode'),
                'key' => $key,
            );
        }
        \Yii::$app->user->login($user, 3600*24*30);
        return array('errorCode' => DictionaryLogic::getErrorCode('Success'));
    }
}
