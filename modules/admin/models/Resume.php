<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Resume.php
 * $Id: Resume.php v 1.0 2015-11-06 14:51:05 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-07 23:07:04 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;

/**
 * This is the model class for table "Goods".
 *
 */
class Resume extends \app\components\BaseMongoActiveRecord {
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'Resume';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function rules() {
        return [
            // name
            ['name', 'required', 'on' => ['create', 'update']],
            ['name', 'string', 'min' => 2, 'max' => 4],
            ['name', 'trim'],

            //phoneno
            ['phoneno', 'required', 'on' => ['create', 'update']],
            ['phoneno', 'match', 'pattern' => '/^\d+$/'],
            ['phoneno', 'string', 'min' => 10, 'max' => 11],
            ['phoneno', 'trim'],

            //email
            ['email', 'required', 'on' => ['create', 'update']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'trim'],

            //birth
            ['birth', 'required', 'on' => ['create', 'update']],
            ['birth', 'match', 'pattern' => '/^\d+$/'],
            ['birth', 'string', 'min' => 4, 'max' => 4],

            //attachment
            //['attachment', 'required', 'on' => ['create', 'update']],

            //workingLife
            ['workingLife', 'required', 'on' => ['create', 'update']],
            ['workingLife', 'match', 'pattern' => '/^\d+$/'],
            ['workingLife', 'string', 'min' => 1, 'max' => 2],

            //currComp
            ['currComp', 'required', 'on' => ['create', 'update']],
            ['currComp', 'string', 'min' => 3, 'max' => 14],

            //currPosition
            ['currPosition', 'required', 'on' => ['create', 'update']],
            ['currPosition', 'string', 'min' => 2, 'max' => 14],

            //city
            ['city', 'required', 'on' => ['create', 'update']],
            ['city', 'string', 'min' => 2, 'max' => 25],

            //intro
            ['intro', 'required', 'on' => ['create', 'update']],
            ['intro', 'string', 'min' => 10, 'max' => 4096],

            //eduBackground
            //['eduBackground', 'required', 'on' => ['create', 'update']],
            ];
    }

    public function attributes() {
        return array_merge($this->primaryKey(), ['userId', 
            // 个人信息
            'name', 'phoneno', 'email', 'avatar', 'birth', 'currComp', 'currPosition', 'workingLife', 'city', 'intro',
            // 教育背景
            'eduBackground', // 包含 'school', 'major', 'education', 'graduation'四个字段
            // 求职意向
            'intenCity', 'intenPosition', 'expectSalary', 'expectSalaryType',
            // 附件简历
            'attachment',
            ]);
    }

    public function scenarios() {
        return [
            'create'          => ['name', 'phoneno', 'email', 'avatar', 'birth', 'attachment', 'workingLife', 'currComp', 'currPosition', 'city', 'intro', 'intenCity', 'intenPosition', 'expectSalary', 'expectSalaryType'],
            'update'          => ['name', 'phoneno', 'email', 'avatar', 'birth', 'attachment', 'workingLife', 'currComp', 'currPosition', 'city', 'intro', 'intenCity', 'intenPosition', 'expectSalary', 'expectSalaryType'],
            'default'         => []
            ];
    }

    public function attributeLabels() {
        return [
            'userId' => I18n::text('UserId'),
            'name' => I18n::text('真实姓名'),
            'phoneno' => I18n::text('手机'),
            'email' => I18n::text('邮箱'),
            'avatar' => I18n::text('简历头像'),
            'birth' => I18n::text('出生年份'),
            'currComp' => I18n::text('目前公司'),
            'currPosition' => I18n::text('当前职位'),
            'workingLife' => I18n::text('工作年限'),
            'city' => I18n::text('缩在城市'),
            'intro' => I18n::text('个人介绍'),
            'eduBackground' => I18n::text('教育背景'),
            'school' => I18n::text('你的大学'),
            'major' => I18n::text('你的专业'),
            'education' => I18n::text('你的学历'),
            'graduation' => I18n::text('毕业时间'),
            'intenCity' => I18n::text('意向城市'),
            'intenPosition' => I18n::text('意向岗位'),
            'expectSalary' => I18n::text('期望薪资'),
        ];
    }

    public function load($data, $formName = null) {
        if (parent::load($data, $formName) == false) {
            return false;
        }
        $eduB = array();
        if (isset($data['Resume']['eduBackground']) == false) {
            if (isset($data['Resume']['attachment']) == false) {
                $this->addError('eduBackground', '教育背景信息不能为空');
                return false;
            }
        }
        else {
            foreach ($data['Resume']['eduBackground'] as $row) {
                if ($row['school'] == '' || $row['major'] == '' || $row['education'] == '' ||$row['education'] == '0' || $row['graduation'] == '') {
                    $this->addError('eduBackground', '教育背景信息不能为空');
                    return false;
                }
                $eduB [] = $row;
            }
            $this->eduBackground = $eduB;
        }
        return true;
    }
}
