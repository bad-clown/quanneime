<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Cart.php
 * $Id: Cart.php v 1.0 2015-11-10 15:15:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-10 15:27:46 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "cart".
 *
 */
class Cart extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'cart';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ];
    }

    public function attributes() {
        // isTemp用来标记是不是临时购物车
        return array_merge($this->primaryKey(), ['userId', 'goods', 'isTemp']);
    }

    public function attributeLabels() {
        return [
        ];
    }
}
