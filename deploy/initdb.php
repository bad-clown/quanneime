<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: initdb.php
 * $Id: initdb.php v 1.0 2015-11-10 17:51:42 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-10 11:21:40 $
 * @brief 初始化数据库
 *
 ******************************************************************/

$conf = require('../config/mongodb.php');

$dict = require('./dictionary.php');

try {
    // 连接数据库
    $client = new MongoClient($conf['dsn'], $conf['options']);
    $db = $client->selectDB($conf['options']['db']);

    // 创建数据库索引
    /*$collection = $db->selectCollection('order');
    $collection->ensureIndex(array('orderId' => 1), array('name' => 'orderId'));
    $collection->ensureIndex(array('userId' => 1), array('name' => 'userId'));
    $collection->ensureIndex(array('userId' => 1, 'status' => 1), array('name' => 'userId_status'));

    $collection = $db->selectCollection('orderitem');
    $collection->ensureIndex(array('orderId' => 1), array('name' => 'orderId'));

    $collection = $db->selectCollection('address');
    $collection->ensureIndex(array('userId' => 1), array('name' => 'userId'));

    $collection = $db->selectCollection('comment');
    $collection->ensureIndex(array('goodsId' => 1), array('name' => 'goodsId'));
    $collection->ensureIndex(array('goodsId' => 1, 'publishTime' => 1), array('name' => 'goodsId_publishTime'));

    $collection = $db->selectCollection('goods');
    $collection->ensureIndex(array('name' => 1), array('name' => 'name'));

    $collection = $db->selectCollection('cart');
    $collection->ensureIndex(array('userId' => 1), array('name' => 'userId'));*/

    // 清空并导入dictionary初始数据
    $collection = $db->selectCollection('dictionary');
    $collection->remove();
    $collection->batchInsert($dict);

    // 清空autoinc表并初始化记录
    /*$collection = $db->selectCollection('autoinc');
    $collection->remove();
    $collection->insert(array('key' => 1, 'value' => 0));*/
}
catch (Exception $e) {
    var_dump($e);
}
