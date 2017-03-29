<?php

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use app\components\BaseMongoActiveRecord;
use app\components\Cache;
/**
 * This is the model class for table "dictionary".
 *
 * @property string $recid
 * @property string $idx
 * @property string $key
 * @property string $value
 * @property string $description
 */
class Dictionary extends BaseMongoActiveRecord
{

    /**
    *   存取在cache中的key
    */
    protected static $cacheKey ="sysdictionay";

    public static function originalMap() {
        return self::ensureList();
    }

    public static function allIndexList($i18n = true) {
        $allList = self::ensureList();
        $result = [];
        foreach ($allList as $rowIdx => $row) {
            if($i18n && preg_match(I18n::$I18nValidKeyPattern, $row->value)){
                $row->value = I18n::text($row->value);
            }
            else {
                $row->value = I18n::normalText($row->value);
            }
            $result[] = $row;
        }
        return $result;
    }

    /**
    * 根据idx获取列表
    *   @return array@{key,value}
    */
    public static function indexList($idx,$i18n = true){
        $allList = self::ensureList();
        $result = [];
        foreach ($allList as $rowIdx => $row) {
            if($row->idx == $idx){
                if($i18n && preg_match(I18n::$I18nValidKeyPattern, $row->value)){
                    $row->value = I18n::text($row->value);
                }
                else {
                    $row->value = I18n::normalText($row->value);
                }
                $result[] = $row;
            }
        }
        return $result;
    }

    /**
    * 根据idx获取键值对
    *   @return map
    */
    public static function indexMap($idx,$i18n = true){
        $list = self::indexList($idx,$i18n);
        $resultMap=[];
        foreach ($list as $rowIdx=>$row) {
            if($i18n && preg_match(I18n::$I18nValidKeyPattern, $row->value)){
                $row->value = I18n::text( $row->value);
            }
            else {
                $row->value = I18n::normalText($row->value);
            }
            $resultMap[$row->key] = $row->value;
        }
        return $resultMap;
    }

    /**
    *    根据index,key 获取值
    *   @param i18n : 是否读取多语言信息，默认为true
    */
    public static function indexKeyValue($idx,$key,$i18n = true){
        $map = self::indexMap($idx,$i18n);
        $result = array_key_exists($key,$map)!=1?"":$map[$key];
        if($i18n && preg_match(I18n::$I18nValidKeyPattern, $result)){
            $result = I18n::text($result);
        }
        else {
            $result = I18n::normalText($result);
        }
        return $result;
    }

    

    /**
    *   确保读取完整的Dictionay数据
    */
    protected static function ensureList(){
        $resultList  = Cache::get(self::$cacheKey);
        if($resultList === false || count($resultList)==0){
            $resultList = self::find()->select(["idx","key","value"])->all();
            Cache::set(self::$cacheKey,$resultList);
        }
        return $resultList;
    }

    /**
    *   日期类型的字段在给view前先格式化成用户需要的格式
    */
    public function afterFind(){
        /*if(preg_match(I18n::$I18nValidKeyPattern, $this->value)){
            $this->value = I18n::text($this->value);
        }*/
        parent::afterFind();
        return true;
    }

    /**
    *  日期类型的字段  新增修改 保存的时候将用户设置的格式转换成数据中存储的格式
    */
    public function beforeSave($insert){
        if (parent::beforeSave($insert)) {
            Cache::delete(self::$cacheKey) ;
            return true;
        } else {
            return false;
        }
    }

    public function beforeDelete(){
        if(parent::beforeDelete()){
            Cache::delete(self::$cacheKey) ;
            return true;
        }else{
            return false;
        }
    }
    /**
     * @return string the name of the index associated with this ActiveRecord class.
     */
    public static function collectionName()
    {
        return 'dictionary';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    /**
     * @return array list of attribute names.
     */
    public function attributes()
    {
        return array_merge($this->primaryKey(), ['idx', 'key', 'value', 'description']);
    }



    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idx', 'key'], 'required'],
            [['idx', 'key', 'description'], 'string', 'max' => 50],
            [['value'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idx' => I18n::text('Index'),
            'key' => I18n::text('Key'),
            'value' => I18n::text('Value'),
            'description' => I18n::text('Description'),
        ];
    }
}
