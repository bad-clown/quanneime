<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: OrderController.php
 * $Id: OrderController.php v 1.0 2015-11-10 00:16:25 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-10 00:36:26 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\OrderLogic;
use app\modules\admin\models\AutoInc;

/**
 * This command test order logic functions.
 *
 */
class OrderController extends Controller {
    /**
     * 获得AutoInc的下一个值
     */
    public function actionInc() {
        echo AutoInc::next();
        echo "\n";
    }

    /**
     * 生成orderId
     */
    public function actionNewOrderId() {
        echo OrderLogic::generateOrderId();
        echo "\n";
    }
}
