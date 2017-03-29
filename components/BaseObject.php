<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: BaseObject.php
 * $Id: BaseObject.php v 1.0 2015-10-28 21:01:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-10-29 23:29:07 $
 * @brief
 *
 ******************************************************************/

namespace app\components;

use Yii;

class BaseObject extends \Yii\base\Object {
    public static function ensureMongoId($id) {
        if (empty($id)) {
            return new \MongoId();
        }
        if (is_string($id)) {
            try {
                $id = new \MongoId($id);
            }
            catch (\MongoException $e) {
                $id = new \MongoId();
            }
        }
        return $id;
    }
}
