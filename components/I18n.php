<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: I18n.php
 * $Id: I18n.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-13 22:15:41 $
 * @brief
 *
 ******************************************************************/

namespace app\components;
use Yii;

class I18n
{

    public  static $I18nValidKeyPattern = '/\`[^`]+`/';

    /**
    *   get translation 
    *   @param $key 可以是一个key，也可以是带`key`的字符串
    *   @return 返回对应的翻译，语言先取用户自定义语言，然后取英语，还没拿到就直接返回key
    */
    public static function text($key){
        if(empty($key)) return $key;

        preg_match_all(self::$I18nValidKeyPattern,$key, $matches);//没有匹配的也返回一个数组？？？


        if(count($matches)==1 && count($matches[0])==0){ // just  a key
            return  \Yii::t('app',$key);
        }else{ // a string contains `key`
            $result = $key;
            foreach ($matches[0] as $mIdx => $m) {
                if(!empty($m)){
                    $result = preg_replace('/'.preg_quote($m).'/', self::text(substr($m,1,-1)), $result);
                }
            }
            return $result;
        }
    }

    // 返回去除``后的原字符串，比如'`key`'，返回'key'.
    public static function normalText($key) {
        if(empty($key)) return $key;
        preg_match_all(self::$I18nValidKeyPattern,$key, $matches);//没有匹配的也返回一个数组？？？
        if(count($matches)==1 && count($matches[0])==0){ // just  a key
            return  $key;
        }else{ // a string contains `key`
            $result = $key;
            foreach ($matches[0] as $mIdx => $m) {
                if(!empty($m)){
                    $result = preg_replace('/'.preg_quote($m).'/', substr($m,1,-1), $result);
                }
            }
            return $result;
        }
    }

}
