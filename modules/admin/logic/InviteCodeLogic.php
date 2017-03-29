<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: InviteCodeLogic.php
 * $Id: InviteCodeLogic.php v 1.0 2015-12-27 20:54:12 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-12-28 23:19:21 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\InviteCode;
use app\modules\admin\models\AutoInc;

class InviteCodeLogic extends BaseObject {
    public static function generateCode() {
        $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $rand = $code[rand(0,25)]
            .strtoupper(dechex(date('m')))
            .date('d').substr(time(),-5)
            .substr(microtime(),2,5)
            .sprintf('%02d',rand(0,99));
        $a = md5($rand, true);
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUV';
        $d = '';
        for($f = 0; $f < 8; $f++) {
            $g = ord( $a[ $f ] );
            $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ];
        }
        return $d;
    }

    public static function insert($count) {
        $count = (int)$count;
        $data = array();
        for ($i = 0; $i < $count; $i++) {
            $data[] = array('code' => self::generateCode(), 'userid' => null, 'status' => 100);
        }
        if (!empty($data)) {
            return InviteCode::getCollection()->batchInsert($data);
        }
        return 0;
    }

    public static function validInviteCode($code) {
        $rows = InviteCode::find()->where(["code"=>$code])->all();
        if ($rows==null || count($rows)==0) {
            return false;
        }else{
            if($rows[0]->status!=100){
                return false;
            }
        }
        return true;
    }
}
