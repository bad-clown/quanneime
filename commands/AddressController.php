<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: AddressController.php
 * $Id: AddressController.php v 1.0 2015-11-06 16:30:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-06 17:25:03 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\AddressLogic;

/**
 * This command test address logic functions.
 *
 */
class AddressController extends Controller {

    /**
     * 添加一个收货地址
     * @name 收件人姓名
     * @phone 联系手机
     * @zip 邮编
     * @province 省
     * @city 市
     * @district 区
     * @detail 详细地址
     */
    public function actionAdd($name, $phone, $zip, $province, $city, $district, $detail) {
        $param = array(
            'name' => $name,
            'phone' => $phone,
            'zip' => $zip,
            'province' => $province,
            'city' => $city,
            'district' => $district,
            'detail' => $detail
        );
        var_dump(AddressLogic::addAdress($param));
    }

    /**
     * 获取所有地址列表
     */
    public function actionList() {
        $addrList = AddressLogic::getAllAddress();
        $ret = array();
        foreach ($addrList as $addr) {
            $ret[] = $addr->getAttributes();
        }
        var_dump($ret);
    }

    /**
     * 修改一个地址的详细地址
     * @param $addrId 地址id
     * @param $detail 新的详情
     */
    public function actionUpdate($addrId, $detail) {
        var_dump(AddressLogic::updateAdress($addrId, array('detail' => $detail)));
    }

    /**
     * 设置默认地址
     * @param $addrId 新的默认地址id
     */
    public function actionSetDefault($addrId) {
        var_dump(AddressLogic::setDefaultAddress($addrId));
    }

    /**
     * 删除一个地址
     * @param @addrId 地址id
     */
    public function actionDelete($addrId) {
        var_dump(AddressLogic::deleteAddress($addrId));
    }
}
