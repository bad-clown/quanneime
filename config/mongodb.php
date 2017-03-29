<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: mongodb.php
 * $Id: mongodb.php v 1.0 2015-10-27 17:18:41 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-02-18 15:38:51 $
 * @brief
 *
 ******************************************************************/

return [
    'class' => '\yii\mongodb\Connection',
    'dsn' => 'mongodb://work:work@127.0.0.1:27017/quannei',
    'options' => [
        'db' => 'quannei',
        'journal' => true,
        //'username' => 'work',
        //'password' => 'work',
    ],
];
