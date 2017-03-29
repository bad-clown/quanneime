<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Comment.php
 * $Id: Comment.php v 1.0 2015-11-02 11:18:22 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-02 20:07:44 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\models\I18n;

/**
 * This is the model class for table "Comment".
 *
 */
class Comment extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'comment';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['goodsId', 'userId', 'content', 'helpful', 'publishTime', 'delete']);
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
