<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CategoryController.php
 * $Id: CategoryController.php v 1.0 2015-11-02 21:02:19 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-02 21:55:29 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\CategoryLogic;

/**
 * This command test category logic functions.
 *
 */
class CategoryController extends Controller {

    /**
     * 添加一个分类
     * @param parent 父分类id
     */
    public function actionAdd($name, $parent = null) {
        var_dump(CategoryLogic::addCategory($name, $parent));
    }

    /**
     * 获取顶层分类
     */
    public function actionTop() {
        $ret = array();
        $top = CategoryLogic::getAllTopCategory();
        foreach ($top as $row) {
            $ret[] = $row->getAttributes();
        }
        print_r($ret);
    }

    /**
     * 获取子分类
     * @param $cateId 父分类id
     */
    public function actionChildren($cateId) {
        $ret = array();
        $cateList = CategoryLogic::getChildrenCategory($cateId);
        foreach ($cateList as $cate) {
            $ret[] = $cate->getAttributes();
        }
        print_r($ret);
    }

    /**
     * 获取以cateId为根的所有子分类树
     * @param $cateId 根节点id
     */
    public function actionTree($cateId) {
        $ret = array();
        $cateList = CategoryLogic::getCategoryTree($cateId);
        foreach ($cateList as $cate) {
            $ret[] = $cate->getAttributes();
        }
        print_r($ret);
    }
}
