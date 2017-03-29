<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsLogic.php
 * $Id: GoodsLogic.php v 1.0 2015-10-29 21:01:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-21 21:59:28 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use yii\helpers\ArrayHelper;
use app\components\BaseObject;
use app\modules\admin\models\Goods;
use app\modules\admin\models\Cart;
use app\modules\admin\models\Order;
use app\modules\admin\models\OrderItem;

class GoodsLogic extends BaseObject {
    public static function addGoods($params) {
        $goods = new Goods();
        $goods->name = $params['name'];
        $goods->category = $params['category'];
        $goods->price = floatval($params['price']);
        $goods->otherPrice = $params['otherPrice'];
        $goods->cashBack = floatval($params['cashBack']);
        $goods->buyLimit = intval($params['buyLimit']);
        $goods->count = intval($params['count']);
        $goods->status = intval($params['status']);
        $goods->detail = $params['detail'];
        $goods->recomIndex = floatval($params['recomIndex']);
        $goods->recomReason = $params['recomReason'];
        $goods->publishTime = intval($params['publishTime']);
        $goods->createTime = NOW;
        $goods->pics = [];
        //$goods->pics[] = array('thumb'
        foreach ($params as $key => $value) {
            $goods->$key = $value;
        }
    }

    public static function getGoodsById($id) {
        $id = self::ensureMongoId($id);
        $item = Goods::find()->where(['_id'=> $id, 'delete' => false])->one();
        if ($item != null) {
            $item->buyCount = self::getGoodsCountInOrder($item->_id);
            $item->cartCount = self::getGoodsCountInCart($item->_id);
        }
        return $item;
    }

    public static function getGoodsCountInCart($id) {
        $uid = CartLogic::getUserId();
        $cart = Cart::find()->where(['userId' => $uid])->one();
        if ($cart != null && $cart->goods != null) {
            foreach($cart->goods as $gId=>$c){
                if($gId ==(string)$id){
                    return intval($c);
                }
            }
        }
        return 0;
    }

    public static function getGoodsCountInOrder($id) {
        $total = 0;
        if(!\Yii::$app->user->isGuest) {
            $refreshTime = mktime(0, 0, 0, date('m'), date('d'), date('Y')) + intval(DictionaryLogic::indexKeyValue('BuyLimit', 'RefreshTime')) * 3600;
            if ($refreshTime > NOW) {
                $refreshTime -= 24 * 3600;
            }
            $userValidOrders = Order::find()
                ->select(["orderId"])
                ->where(["userId"=>\Yii::$app->user->identity->_id,"status"=>['$ne'=>700]])
                ->andWhere(['time' => ['$gte' => $refreshTime]])
                ->all();
            $userValidOrderIds = ArrayHelper::getColumn($userValidOrders,"orderId");
            $itemList = OrderItem::find()->select(['_id'=>false, 'count'])->where(['goodsId' => $id])->andWhere(['orderId' => $userValidOrderIds])->all();
            foreach ($itemList as $item) {
                $total += intval($item->count);
            }
        }
        return $total;
    }

    public static function totalPageCount($key='',$category, $pageNum) {
        $pageNum = intval($pageNum) <= 0 ? 1 : intval($pageNum);
        $query = Goods::find();
        if(strlen($key)>0){
            $query->where(['like','name',$key]);
        }
        $query->andWhere(['status' => '100']);
        $query = $query->andWhere(['mainGoodsId' => null]);
        $query = $query->andWhere(['delete' => false]);
        return ceil($query->count() / $pageNum);
    }

    public static function getGoodsList($key = '', $category = null, $page = 1, $order = 1, $pageNum = 15) {
        $offset = (intval($page) - 1) * intval($pageNum);
        $orderBy = DictionaryLogic::indexKeyValue('OrderType', intval($order), false);
        if ($orderBy == "") {
            $orderBy = 'recomIndex';
        }
        $query = Goods::find()->select(['name', 'price','vipPrice', 'pics', 'count', 'status', 'recomIndex', 'recomReason','cashBack']);
        if (strlen($key) > 0) {
            $query = $query->where(['like', 'name', $key]);
            $query = $query->orWhere(['like', 'detail', $key]);
        }
        if ($category) {
            $query = $query->andWhere(['category' => $category]);
        }
        $query = $query->andWhere(['status' => '100']);
        $query = $query->andWhere(['delete' => false]);
        $query = $query->andWhere(['mainGoodsId' => null]);
        $query = $query->orderBy($orderBy);
        $query = $query->offset($offset);
        $query = $query->limit($pageNum);
        return $query->all();
    }

    public static function getGoodsListByIdList ($idList) {
        return Goods::find()->where(['_id' => $idList])->all();
    }
}
