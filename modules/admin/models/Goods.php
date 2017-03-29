<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: Goods.php
 * $Id: Goods.php v 1.0 2015-10-29 19:38:13 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-22 20:18:48 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\models;

use Yii;
use app\components\I18n;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\modules\admin\logic\CartLogic;
use app\modules\admin\models\Cart;
use app\modules\admin\models\Order;
use app\modules\admin\models\OrderItem;
use yii\helpers\ArrayHelper;
use app\modules\admin\logic\DictionaryLogic;

/**
 * This is the model class for table "Goods".
 *
 */
class Goods extends \app\components\BaseMongoActiveRecord {
    public $buyCount = 0;
    public $cartCount = 0;
    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'goods';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['createTime'],
                ],
            ],
        ];
    }

    public function rules() {
        return [
            [['_id','name', 'category', 'price', 'vipPrice', 'otherPrice',
                'cashBack', 'buyLimit', 'pics', 'detail', 'count', 'status', 'recomIndex', 'recomReason',
                'createTime', 'publishTime','delete','require'],"safe"],
            [ ["name","category","price","recomIndex","status"],"required"],
            [["price","cashBack","buyLimit","count","recomIndex"],"integer"],
            [["recomReason"],"string","max"=>10000],
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->price = (float)$this->price;
            $this->cashBack = (float)$this->cashBack;
            $this->buyLimit = (int)$this->buyLimit;
            $this->count = (int)$this->count;
            $this->recomIndex = (float)$this->recomIndex;
            if ($this->vipPrice == null) {
                $this->vipPrice = array();
            }
            $vips = DictionaryLogic::indexMap('VIP');
            $vipPrice = $this->vipPrice;
            foreach ($vips as $vk => $vv) {
                if (isset($this->vipPrice[$vk]) == false) {
                    $vipPrice[$vk] = 0;
                }
            }
            $this->vipPrice = $vipPrice;
            return true;
        }
        else {
            return false;
        }
    }

    public function afterFind() {
    }

    /**
     * pics结构： [{thumb:'', src:'', primary: true},...]
     */
    public function attributes() {
        return array_merge($this->primaryKey(), ['name', 'category', 'price', 'otherPrice',
            'cashBack', 'buyLimit','pics', 'detail', 'count', 'status', 'recomIndex', 'recomReason',
            'createTime', 'publishTime','delete',
            // 子商品相关，features表示可选的属性（颜色、容量等），attr表示各个子商品中存在差异的字段，比如price、count等。mainGoodsId指向主商品
            // 如果mainGoodsId未null表示这个商品本身就是主商品。
            // featureKey则是由features里的值用','连接起来的一个字符串
            'features', 'attr', 'mainGoodsId', 'featureKey',
            'vipPrice','require'// vipPrice 各个VIP级别加价 [level=>vipPrice] ,require:["VIP1","VIP2"] 数组级别方可购买该商品
            ]);
    }

    public function attributeLabels() {
        return [
            'name' => I18n::text('商品名称'), // 商品名称
            'category' => I18n::text('商品分类'), // 商品分类
            'price' => I18n::text('价格'), // 平台价格
            'otherPrice' => I18n::text('其他平台价格'), // 其他平台价格
            'cashBack' => I18n::text('现金返现'), // 现金返现
            'buyLimit' => I18n::text('限购数量'), // 限购数量
            'pics' => I18n::text('商品展示图'), // 商品展示图
            'detail' => I18n::text('商品详情'), // 商品详情
            'count' => I18n::text('库存数量'), // 商品数量
            'status' => I18n::text('商品状态'), // 商品状态
            'recomIndex' => I18n::text('推荐指数'), // 推荐指数
            'recomReason' => I18n::text('推荐理由'), // 推荐理由
            'createTime' => I18n::text('创建时间'), // 商品创建时间
            'publishTime' => I18n::text('发布时间'), // 商品上市发布时间
            'vipPrice' => I18n::text('提货加价'),
            'require' => I18n::text('上架等级'),
        ];
    }

    // 保存整个子商品
    public function saveSubGoods($postData) {
        $features = isset($postData['features'])?$postData["features"]:[];
        $featureKeys = array();
        foreach ($features as $feature) {
            $featureKeys[] = $feature['name'];
        }
        $subGoods = $this->getAllSubGoodsModels();
        $holdGoods = array();
        $items = isset($postData['items'])?$postData["items"]:[];
        $itemCount = 0;
        $result = true;
        foreach ($items as $key => $item) {
            // 1. 创建商品对象，默认第一个为主商品，其他都是第一个的子商品
            if ($itemCount == 0) {
                $model = $this;
                $model->mainGoodsId = null;
            }
            else {
                // 1.1 子商品首先从主商品中copy属性，然后设置mainGoodsId指向主商品
                // 如果之前没有保存过这个子商品，那么
                $model = isset($subGoods[$key]) ? $subGoods[$key] : new Goods();
                $model->copy($this);
                $model->mainGoodsId = $this->_id;
            }
            $holdGoods[] = $key;
            $model->featureKey = $key;
            // 2. 设置商品的features
            $f = array();
            $featureValues = split(',', $key);
            for ($i = 0; $i < count($featureKeys); $i++) {
                $f[$featureKeys[$i]] = $featureValues[$i];
            }
            $model->features = $f;
            // 2. 设置商品的差异属性
            $model->attr = array_keys($item);
            foreach ($item as $attrKey => $attrValue) {
                $model->$attrKey = $attrValue;
            }
            $result &= $model->save();
            if ($result == false) {
                break;
            }
            $itemCount++;
        }
        // 删除没再使用的商品
        if ($result == true && count($items)>0) {
            foreach ($subGoods as $key => $goods) {
                if (!in_array($key, $holdGoods)) {
                    $goods->delete = true;
                    $goods->save();
                }
            }
        }
        return $result;
    }

    // 返回所有子商品的model对象
    public function getAllSubGoodsModels() {
        $items = Goods::find()
                ->where(['mainGoodsId' => ($this->mainGoodsId==null? $this->_id:$this->mainGoodsId), 'delete' => false])
                ->orWhere(['_id' => ($this->mainGoodsId==null? $this->_id:$this->mainGoodsId), 'delete' => false])
                ->all();
        $subGoods = array();
        foreach ($items as $item) {
            $subGoods[$item->featureKey] = $item;
        }
        return $subGoods;
    }

    // 获取返回给前端的subgoods数据，格式按和前端约定的格式来，和保存时提交上来的subgoods格式一致
    public function getSubGoodsData() {
        $allGoods = array();
        $allGoods[$this->featureKey] = $this;
        $allGoods = array_merge($allGoods, $this->getAllSubGoodsModels());
        $subgoods = array();
        $subgoods['features'] = array();
        if(is_array($this->features)){
            foreach ($this->features as $fk => $fv) {
                $subgoods['features'][$fk] = array('name' => $fk, 'values' => array());
            }
        }
        $subgoods['items'] = array();
        foreach ($allGoods as $goods) {
            if(is_array($goods->features)){
                foreach ($goods->features as $fk => $fv) {
                    if (!isset($subgoods['features'][$fk])) {
                        $subgoods['features'][$fk] = array('name' => $k, 'values' => array());
                    }
                    if (!in_array($fv, $subgoods['features'][$fk]['values'])) {
                        $subgoods['features'][$fk]['values'][] = $fv;
                    }
                }
            }
            $subgoods['items'][$goods->featureKey] = array();
            if(is_array($goods->attr)){
                foreach ($goods->attr as $attr) {
                    $subgoods['items'][$goods->featureKey][$attr] = $goods->$attr;
                }
                $subgoods['items'][$goods->featureKey]["id"] = (string)$goods->_id;
            }
        }
        $subgoods['features'] = array_values($subgoods['features']);
        return $subgoods;
    }

    // 复制$goods里的属性到$this中
    private function copy($goods) {
        foreach ($goods->getAttributes() as $attr => $value) {
            if ($attr != $this->primaryKey()[0]) {
                $this->$attr = $goods->$attr;
            }
        }
    }
}
