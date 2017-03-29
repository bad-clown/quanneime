<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GridActionColumn.php
 * $Id: GridActionColumn.php v 1.0 2015-10-28 20:31:15 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-10-29 21:27:18 $
 * @brief
 *
 ******************************************************************/

namespace app\components;

use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;

Class GridActionColumn extends ActionColumn{

	/**
     * Initializes the default button rendering callbacks
     *	@override 生成的icon不兼容IE7  and I18n
     */
    public function init()
    {
        parent::init();
        if (isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">&#xe105;</span>', $url, [
                    'title' =>( 'View'),
                    'data-pjax' => '0',
                ]);
            };
        }
        if (isset($this->buttons['update'])) {
            $this->buttons['update'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">&#x270f;</span>', $url, [
                    'title' => ('Update'),
                    'data-pjax' => '0',
                ]);
            };
        }
        if (isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model) {
                return Html::a('<span class="glyphicon">&#xe020;</span>', $url, [
                    'title' => ("Delete"),
                    'data-confirm' => ("confirmdelete"),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
            };
        }
    }
}
