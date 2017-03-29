<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: DictionaryLogic.php
 * $Id: DictionaryLogic.php v 1.0 2015-09-03 15:26:57 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-13 22:15:51 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Dictionary;

class DictionaryLogic extends BaseObject {
    public static function getErrorCode($key) {
        return (int)Dictionary::indexKeyValue('ErrorCode', $key);
    }

    public static function getErrorMsg($errorCode) {
        $map = Dictionary::indexMap('ErrorCode', false);
        $map = array_flip($map);
        return Dictionary::indexKeyValue('ErrorMsg', $map[$errorCode]);
    }

    public static function indexList($idx, $i18n = true) {
        return Dictionary::indexList($idx, $i18n);
    }

    public static function indexMap($idx, $i18n = true) {
        return Dictionary::indexMap($idx, $i18n);
    }

    public static function indexKeyValue($idx, $key, $i18n = true) {
        return Dictionary::indexKeyValue($idx, $key, $i18n);
    }

    public static function indexKey($idx, $value) {
        $idxMap = Dictionary::indexMap($idx, false);
        $valueKeyMap = array_flip($idxMap);
        return isset($valueKeyMap[$value]) ? $valueKeyMap[$value] : "";
    }
}
