<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CartLogic.php
 * $Id: CartLogic.php v 1.0 2015-11-10 15:21:44 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-24 10:52:54 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use yii\web\Cookie;
use yii\web\CookieCollection;
use app\components\BaseObject;
use app\modules\admin\models\Cart;
use app\modules\admin\models\Order;
use app\modules\admin\models\OrderItem;

class CartLogic extends BaseObject {
    public static function getUserId() {
        if (\Yii::$app->user->isGuest) {
            $cookies = Yii::$app->request->cookies;
            $name = DictionaryLogic::indexKeyValue('App', 'CartCookieName', false);
            $tmpId = $cookies->getValue($name, null);
            if ($tmpId == null) {
                $cookies = Yii::$app->response->cookies;
                $tmpId = new \MongoId();
                $cookies->add(new Cookie(['name' => $name, 'value' => $tmpId, 'expire' => NOW + 86400]));
            }
            return $tmpId;
        }
        else {
            return \Yii::$app->user->identity->_id;
        }
    }

    public static function genNewCart() {
        $cart = new Cart;
        $cart->userId = self::getUserId();
        $cart->isTemp = \Yii::$app->user->isGuest;
        $cart->goods = array();
        return $cart;
    }

    /**
     * 查询购物车里商品种类数目
     */
    public static function getGoodsCount() {
        $uid = self::getUserId();
        $cart = Cart::find()->where(['userId' => $uid])->one();
        if ($cart == null) {
            return 0;
        }
        return count($cart->goods);
    }

    /**
     * 添加获取购物车商品列表
     */
    public static function getGoodsList() {
        $uid = self::getUserId();
        $cart = Cart::find()->where(['userId' => $uid])->one();
        if ($cart == null || $cart->goods == null) {
            return array();
        }
        $goodsIdList = array();
        foreach ($cart->goods as $goodsId => $count) {
            $goodsIdList[] = self::ensureMongoId($goodsId);
        }
        $goodsList = GoodsLogic::getGoodsListByIdList($goodsIdList);
        $ret = array();
        foreach ($goodsList as $goods) {
            $item = $goods->getAttributes();
            $item['countCount'] = $goods->count;//库存 sorry 变量名枯竭了。。。
            $item['count'] = $cart->goods[(string)$goods->_id];
            $ret[] = $item;
        }
        return $ret;
    }

    /**
     * 修改购物车中某一个商品的数量
     */
    public static function updateGoodsCount($id, $count) {
        $cart = Cart::find()->where(['userId' => self::getUserId()])->one();
        if ($cart == null || $cart->goods == null || isset($cart->goods[(string)$id]) == false) {
            return DictionaryLogic::getErrorCode('NoSuchGoods');
        }
        $cartGoods = $cart->goods;
        $count = intval($count) < 1 ? 1 : intval($count);
        $goods = GoodsLogic::getGoodsById($id);
        if ($goods == null) {
            return DictionaryLogic::getErrorCode('NoSuchGoods');
        }
        $count = min($count, $goods->buyLimit - $goods->buyCount, $goods->count);
        $count = max($count, 0);
        $cartGoods[(string)$id] = intval($count);
        $cart->goods = $cartGoods;
        $cart->save();
        return DictionaryLogic::getErrorCode('Success');
    }

    /**
     * 将某个商品从购物车中删除
     */
    public static function delGoods($id) {
        $cart = Cart::find()->where(['userId' => self::getUserId()])->one();
        if ($cart == null || $cart->goods == null || isset($cart->goods[(string)$id]) == false) {
            return DictionaryLogic::getErrorCode('Success');
        }
        $cartGoods = $cart->goods;
        if (isset($cartGoods[(string)$id])) {
            unset($cartGoods[(string)$id]);
            $cart->goods = $cartGoods;
            $cart->save();
        }
        return DictionaryLogic::getErrorCode('Success');
    }

    /**
     * 将某个商品添加进购物车
     */
    public static function addGoodsToCart($id, $count) {
        $cart = Cart::find()->where(['userId' => self::getUserId()])->one();
        if ($cart == null) {
            $cart = self::genNewCart();
        }
        $cartGoods = $cart->goods;
        $count = intval($count) < 1 ? 1 : intval($count);
        $goods = GoodsLogic::getGoodsById($id);
        if ($goods == null) {
            return DictionaryLogic::getErrorCode('NoSuchGoods');
        }
        if ($goods->status != '100') {
            return DictionaryLogic::getErrorCode('NotInSell');
        }
        if ($goods->require != null && in_array(\Yii::$app->user->identity->vipLevel, $goods->require) == false) {
            return DictionaryLogic::getErrorCode('CannotBuy');
        }
        if (isset($cartGoods[(string)$id])) {
            $count += $cartGoods[(string)$id];
        }
        $count = min($count, $goods->buyLimit - $goods->buyCount, $goods->count);
        $count = max($count, 0);
        $cartGoods[(string)$id] = intval($count);
        $cart->goods = $cartGoods;
        $cart->save();
        return DictionaryLogic::getErrorCode('Success');
    }

    /**
     * 提交商品到订单
     */
    public static function submitOrder($orderId, $goodsList) {
        $result = ["status"=>0,"id"=>""];
        $totalMoney = 0;
        $cashBack = 0;
        $orderItemList = array();
        $goodsModelList = array();
        foreach ($goodsList as $item) {
            $goods = GoodsLogic::getGoodsById($item["id"]);
            if ($goods == nulL) {
                continue;
            }
            if ($goods->status != '100') {
                $result['status'] = DictionaryLogic::getErrorCode('NotInSell');
                $result['goodsId'] = $item['id'];
                return $result;
            }
            if ($item['count'] > $goods['count']) {
                $result['status'] = DictionaryLogic::getErrorCode('NotEnough');
                $result['goodsId'] = $item['id'];
                return $result;
            }
            $price = intval($goods['price']) + intval($goods->vipPrice[\Yii::$app->user->identity->vipLevel]);
            $totalMoney  = $totalMoney + $price*intval($item["count"]);
            $cashBack = $cashBack + $goods["cashBack"]==null?0:floatval($goods["cashBack"]) * intval($item["count"]);
            $orderItem = array();
            $orderItem['orderId'] = $orderId;
            $orderItem['goodsId'] = $goods->_id;
            $orderItem['name'] = $goods->name;
            $orderItem['price'] = $price;
            $orderItem['totalMoney'] = $price * intval($item['count']);
            $orderItem['cashBack'] = $goods->cashBack * intval($item['count']);
            $orderItem['count'] = (int)$item['count'];
            $orderItemList[] = $orderItem;
            $goods->count -= $item['count'];
            $goodsModelList[] = $goods;
        }
        if (count($orderItemList) <= 0) {
            $result["status"] = DictionaryLogic::getErrorCode('NoGoodsInCart');
            return $result;
        }

        $order = new Order();
        $order["orderId"] = $orderId;
        $order["userId"] = \Yii::$app->user->identity->_id;
        $order["time"] = NOW;
        $order["totalMoney"]  = $totalMoney;
        $order["cashBack"] = $cashBack;
        $order["status"] = DictionaryLogic::indexKeyValue('OrderStatus', 'Submitted', false);
        $order["statusRecord"] = array(array($order['status'], NOW));

        if (count(OrderItem::getCollection()->batchInsert($orderItemList)) != count($orderItemList)) {
            $result["status"]   =DictionaryLogic::getErrorCode('BatchInsertFail');
            return $result;
        }
        if (!$order->save()) {
            $result["status"]= DictionaryLogic::getErrorCode('SaveToDbFail');
        }
        foreach ($goodsModelList as $goods) {
            $goods->save();
        }
        $result["id"] = (string)$order["_id"];
        $result["status"] = DictionaryLogic::getErrorCode('Success');

        // 将加入订单的物品从购物车中删除
        $cart = Cart::find()->where(['userId' => self::getUserId()])->one();
        $cartGoods = $cart->goods;
        foreach ($goodsList as $item) {
            if (isset($cartGoods[(string)$item['id']])) {
                unset($cartGoods[(string)$item['id']]);
            }
        }
        $cart->goods = $cartGoods;
        $cart->save();
        return $result;
    }

    public static function mergeTempCart() {
        $name = DictionaryLogic::indexKeyValue('App', 'CartCookieName', false);
        $cookies = Yii::$app->request->cookies;
        $tmpId = $cookies->getValue($name, null);
        if ($tmpId == null) {
            return;
        }

        $tempcart = Cart::find()->where(['userId' => $tmpId])->one();
        if ($tempcart == null) {
            return;
        }

        $uid = self::getUserId();
        $cart = Cart::find()->where(['userId' => $uid])->one();
        if ($cart == null) {
            $cart = self::genNewCart();
        }
        $cartGoods = $cart->goods;
        $cartGoods = array_merge($cartGoods, $tempcart->goods);
        $cart->goods = $cartGoods;
        if ($cart->save()) {
            $tempcart->delete();
        }
    }
}
