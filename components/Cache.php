<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Cache.php
 * $Id: Cache.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-25 22:59:33 $
 * @brief
 *
 ******************************************************************/

namespace app\components;

use Yii;

class Cache extends \Yii\base\Object {
    private static $prefix = "qn_";

    public static function get($key) {
        return Yii::$app->cache->get(self::$prefix . $key);
    }

    public static function set($key, $value, $duration = 0) {
        Yii::$app->cache->set(self::$prefix . $key, $value);
    }

    public static function delete($key) {
        Yii::$app->cache->delete(self::$prefix . $key);
    }
}
