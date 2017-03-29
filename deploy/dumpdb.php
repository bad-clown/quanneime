<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: dumpdb.php
 * $Id: dumpdb.php v 1.0 2015-11-10 18:09:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-03-10 11:26:04 $
 * @brief 导出dictionary表的数据
 *
 ******************************************************************/

$conf = require('../config/mongodb.php');

try {
    $file = null;
    // 写文件头
    $file = fopen('dictionary.php', 'w');
    fwrite($file, "<?php\n\nreturn array(\n");

    // 连接数据库
    $client = new MongoClient($conf['dsn'], $conf['options']);
    $db = $client->selectDB($conf['options']['db']);

    // 获取游标
    $collection = $db->selectCollection('dictionary');
    $cursor = $collection->find();

    // 写数据
    foreach ($cursor as $doc) {
        $str = "    array(";
        $str .= "'idx' => '" . $doc['idx'] . "', ";
        $str .= "'key' => '" . $doc['key'] . "', ";
        $str .= "'value' => '" . $doc['value'] . "', ";
        $str .= "'description' => '" . $doc['description'] . "'";
        $str .= "),\n";
        fwrite($file, $str);
    }

    // 写文件尾
    fwrite($file, ");\n");
    fclose($file);
}
catch (Exception $e) {
    if ($file != null) {
        fclose($file);
    }
    var_dump($e);
}
