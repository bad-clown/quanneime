<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Company.php
 * $Id: Company.php v 1.0 2015-12-27 20:12:58 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-02-26 21:04:45 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use app\modules\admin\logic\DictionaryLogic;

/**
 * This is the model class for table "Company".
 *
 */
class Company extends \app\components\BaseMongoActiveRecord {
    public $jobCount = 0;
    public $hot = false;

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Company';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            [["name", "logo", "description", 'keys', 'detail', 'location' ],"required"],
            ];
    }

    public function attributes() {
        // description是一句话描述，detail是公司简介
        return array_merge($this->primaryKey(), ['name', 'logo', 'description', 'keys', 'detail', 'location']);
    }

    public function attributeLabels() {
        return [
            'name' => I18n::text('公司名称'),
            'logo' => I18n::text('LOGO'),
            'description' => I18n::text('一句话描述'),
            'keys' => I18n::text('公司关键字'),
        ];
    }

    public function afterFind() {
        if ($this->keys == null || trim($this->keys) == '') {
            $this->jobCount = 0;
            return;
        }
        $keyList = preg_split('/ +/', $this->keys);
        $orCond = array('or');
        foreach ($keyList as $k) {
            $orCond[] = array('like', 'company', $k);
        }
        $this->jobCount = Job::find()->where($orCond)->andWhere(['status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')])->count();
        $this->hot = rand(0, 1) == 0 ? false : true;
    }
}
