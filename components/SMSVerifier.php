<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: SMSVerifier.php
 * $Id: SMSVerifier.php v 1.0 2015-07-27 17:00:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-10-24 14:34:50 $
 * @brief
 *
 ******************************************************************/

namespace app\components;

use Yii;
use app\components\Cache;
use app\components\SMSVerifier;

/**
    选用第翼信息短信平台
    参考：http://www.eee1.cn
 */
class SMSVerifier extends \Yii\base\Object {
    private static $codeCachePref = 'sms_c_';
    private static $baseurl = "http://sms.1xinxi.cn/asmx/smsservice.aspx?";
    //private static $username = 'heqikeji@sina.com';
    //private static $pwd = '66D5315B7F990180552EF46337D9';
    //private static $sign = '爱帮买';  // 签名
    private static $username = '欧孚';
    private static $pwd = '18132AB928C32560FE934EA3F795';
    private static $sign = '圈内觅';  // 签名
    private static $type = 'pt';
    private static $verifyTemplate = '短信验证码为：%verifyCode%，%duration%分钟内有效，请勿将验证码提供给他人。';
    private static $notifyTemplate = '您招聘的岗位收到一份新的简历！ 请登录 Quannei.me 进行查看！';

    public static function getCodeCacheKey($mobile) {
        return self::$codeCachePref . $mobile;
    }

    public static function getVerifyCode() {
        return rand(100000, 999999);
    }

    public static function deleteCodeCache($mobile) {
        Cache::delete(self::getCodeCacheKey($mobile));
    }

    public static function verifyCode($mobile, $code, $deleteCache = true) {
        $cacheCode = Cache::get(self::getCodeCacheKey($mobile));
        if ($cacheCode === false) {
            return false;
        }
        if ($cacheCode != $code) {
            return false;
        }
        if ($deleteCache) {
            self::deleteCodeCache($mobile);
        }
        return true;
    }

    public static function sendVerifyCode($mobile) {
        //$cacheCode = Cache::get(self::getCodeCacheKey($mobile));
        //if ($cacheCode === false) {
            $cacheCode = self::getVerifyCode();
        //}
        $duration = 30; // 30分钟
        $content = str_replace(array('%verifyCode%', '%duration%'), array($cacheCode, $duration), self::$verifyTemplate);
        $res = self::send($mobile, $content);
        //var_dump($cacheCode);
        //$res = true;
        if ($res) {
            Cache::set(self::getCodeCacheKey($mobile), $cacheCode, $duration * 60);
            return true;
        }
        else {
            return false;
        }
    }

    public static function sendNotifyMsg($mobile) {
        if (self::send($mobile, self::$notifyTemplate)) {
            return true;
        }
        return false;
    }

    /**
        @mobile: 手机号，可以是单个手机号，也可以是手机号数组
        @content: 发送内容，UTF-8编码
     */
    public static function send($mobile, $content) {
        if (is_array($mobile)) {
            $mobile = implode(',', $mobile);
        }
        $argv = array(
            'name'  => self::$username,
            'pwd'   => self::$pwd,
            'content'   => $content,
            'mobile'    => $mobile,
            'stime'     => '',  // 发送时间，填写时按已填写的时间发送，不填时为当前时间发送
            'sign'      => self::$sign,  // 用户签名
            'type'      => self::$type, // 必须为固定值：pt
            'extno'     => ''       // 可选参数，扩展码，用户自定义扩展码，只能为数字
        );
        $flag = 0;
        $params = "";
        foreach ($argv as $key => $value) {
            if ($flag != 0) {
                $params .= "&";
            }
            $params .= $key . "=" . urlencode($value);
            $flag = 1;
        }
        $url = self::$baseurl . $params;
        $con = substr(file_get_contents($url), 0, 1);
        if ($con == '0') {
            return true;
        }
        else {
            return false;
        }
    }
}
