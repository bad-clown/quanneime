<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: AutoInc.php
 * $Id: AutoInc.php v 1.0 2015-11-10 00:03:01 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-10 00:32:17 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;

/**
 * This is the model class for table "AutoInc".
 *
 */
class AutoInc extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'autoinc';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ];
    }

    public static function next() {
        $update = array('$inc' => array('value' => 1));
        $key = intval(Dictionary::indexKeyValue('AutoInc', 'key'));
        $query = self::find()->where(['key' => $key]);
        return intval($query->modify($update, array('new' => 1))->value);
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['key', 'value']);
    }

    public function attributeLabels() {
        return [
            'goodsId' => I18n::text('userId'), // 被评论的商品id
            'userId' => I18n::text('userId'), // 用户Id
            'content' => I18n::text('content'), // 评论内容
            'helpful' => I18n::text('helpful'), // 认为有用的人数
            'publishTime' => I18n::text('publishTime'), // 发布时间
            'delete' => I18n::text('cashBack'), // 是否删除
        ];
    }
}
