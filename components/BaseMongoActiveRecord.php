<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BaseMongoActiveRecord.php
 * $Id: BaseMongoActiveRecord.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-10-29 21:26:49 $
 * @brief
 *
 ******************************************************************/

namespace app\components;
use yii\mongodb\ActiveRecord;

/**
*  基类ActiveRecord，做一些系统通用的操作
*/
class BaseMongoActiveRecord extends ActiveRecord {

    public static $DBDATEFORMAT = "yyyy-MM-dd";

    public $dbdateformat; // 数据库中默认可以当字符串的日期格式

    public function init(){
        if(empty($this->dbdateformat)){
            $this->dbdateformat = self::$DBDATEFORMAT;
        }
        parent::init();
    }

    public static function getArrayValue($array, $key, $default) {
        return isset($array[$key]) ? $array[$key] : $default;
    }
}
