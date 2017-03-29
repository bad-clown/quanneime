<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Pingxx.php
 * $Id: Pingxx.php v 1.0 2015-11-12 10:19:17 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-27 19:30:35 $
 * @brief  Ping++接口类
 *
 ******************************************************************/

namespace app\components;

use Yii;
use app\modules\admin\logic\DictionaryLogic;

class Pingxx extends \Yii\base\Object {
    /**
     * 判断是否支持次渠道
     */
    private static function checkChannel($channel) {
        $validChannels = array_merge(DictionaryLogic::indexMap('PingxxChannelM'), DictionaryLogic::indexMap('PingxxChannelPC'));
        return in_array((string)$channel, $validChannels, false);
    }

    /**
     * 根据不同的渠道，添加渠道特定的参数参数
     */
    private static function extraParams($channel) {
        switch ($channel) {
        case 'alipay_pc_direct':  // 支付宝网页渠道，增加success_url，要求url不能加上自定义参数
            return array(
                'success_url'   => DictionaryLogic::indexKeyValue('App', 'Host', false) . '/m/account/success',
            );
            break;
        case 'alipay_wap':  // 支付宝手机网页渠道，增加success_url和cancel_url，要求url不能加上自定义参数
            return array(
                'success_url'   => DictionaryLogic::indexKeyValue('App', 'Host', false) . '/webhooks/pingxx/success',
                'cancel_url'    => DictionaryLogic::indexKeyValue('App', 'Host', false) . '/webhooks/pingxx/cancel',
            );
            break;
        /**
         * 银联全渠道手机网页支付渠道，增加result_url，对url没有要求。
         * 渠道会以 POST 方式发送订单信息给 result_url，其中 orderId 对应 Charge 对象里的 order_no。
         */
        case 'upacp_pc':
        case 'upacp_wap':
            return array(
                'result_url'    => DictionaryLogic::indexKeyValue('App', 'Host', false) . '/webhooks/pingxx/result',
            );
            break;
        case 'wx_pub': //微信公众号需要open_id参数
            return array(
                'open_id'       => \Yii::$app->user->identity->openid,
                //'open_id'       => 'o8GeHuLAsgefS_80exEr1cTqekUs',
            );
            break;
        default:
            return array();
            break;
        }
    }

    /**
     * 创建支付对象,成功返回\Pingpp\Charge, 失败返回null
     */
    public static function createCharge($orderNo, $amount, $channel, $clientIp, $subject = '', $body = '') {
        if (self::checkChannel($channel) == false) {
            return null;
        }
        if ($subject == '') {
            $subject = DictionaryLogic::indexKeyValue('Pingxx', 'DefaultSubject');
        }
        if ($body == '') {
            $body = DictionaryLogic::indexKeyValue('Pingxx', 'DefaultBody');
        }
        try {
            \Pingpp\Pingpp::setApiKey(DictionaryLogic::indexKeyValue('Pingxx', 'ApiKey', false));
            return \Pingpp\Charge::create(array(
                'order_no'  => $orderNo,
                'amount'    => $amount * 100, // 我们的系统以元为单位，pingxx以分为单位。
                'app'       => array('id' => DictionaryLogic::indexKeyValue('Pingxx', 'AppId', false)),
                'channel'   => $channel,
                'currency'  => 'cny',
                'client_ip' => $clientIp,
                'subject'   => $subject,
                'body'      => $body,
                'extra'     => self::extraParams($channel),
            ));
        }
        catch (\Pingpp\Error\Base $e) {
            return null;
        }
    }
}
