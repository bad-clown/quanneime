<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Category.php
 * $Id: Category.php v 1.0 2015-11-02 20:04:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-04 23:38:09 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "Category".
 *
 */
class Category extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'category';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [['name', 'parent'], 'safe'],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['name', 'parent','delete']);
    }

    public function attributeLabels() {
        return [
            'name' => I18n::text('分类名称'), // 分类名称
            'parent' => I18n::text('父分类'), // 父分类
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($this->delete == null) {
                $this->delete = false;
            }
            return true;
        }
        else {
            return false;
        }
    }
}
