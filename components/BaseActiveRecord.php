<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BaseActiveRecord.php
 * $Id: BaseActiveRecord.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 21:00:45 $
 * @brief
 *
 ******************************************************************/

namespace app\components;
use yii\db\ActiveRecord;

/**
*  基类ActiveRecord，做一些系统通用的操作
*/
class BaseActiveRecord extends ActiveRecord {
    public function init(){
        parent::init();
    }
}
