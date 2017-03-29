<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CategoryLogic.php
 * $Id: CategoryLogic.php v 1.0 2015-11-02 20:06:50 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-12 21:51:11 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Goods;
use app\modules\admin\models\Category;

class CategoryLogic extends BaseObject {
    /**
     * 添加一个分类
     * @param name 分类名称
     * @param parent 父分类id，如果为null，表示一个顶层分类
     */
    public static function addCategory($name, $parent = null) {
        $cate = new Category;
        $cate->name = $name;
        $cate->parent = ($parent == null ? $parent : self::ensureMongoId($parent));
        return $cate->save();
    }

    /**
     * 获取单个分类条目
     */
    public static function getCategory($cateId) {
        $cateId = self::ensureMongoId($cateId);
        return Category::findOne($cateId);
    }

    /**
     * 获取所有id在$cateIdList中的所有分类条目
     */
    public static function getCategoryByIds($cateIdList) {
        return Category::find()->where(['_id' => $cateIdList])->all();
    }

    /**
     * 获取所有以$rootId为根的分类条目树
     * 采用广度优先算法，查询数据库次数为树的深度
     * 算法：首先获取根条目，然后查询所有根条目的子条目，这些子条目形成一个集合，一次查询出所有这些子条目
     * 然后再把以这些子条目为父节点的条目一次查出，直到没有任何子条目。
     */
    public static function getCategoryTree($rootId) {
        $ret = array();
        $root = self::getCategory($rootId);
        if ($root == null) {
            return $ret;
        }

        $cateList = array();
        $cateIdList = array();

        $cateList[] = $root;
        while (count($cateList) > 0) {
            foreach ($cateList as $cate) {
                $cateIdList[] = $cate->_id;
                $ret[] = $cate;
            }
            $cateList = self::getChildrenCategoryByIds($cateIdList);
            $cateIdList = array();
        }
        return $ret;
    }

    /**
     * 获取所有父节点在$cateIdList中的所有分类条目
     */
    public static function getChildrenCategoryByIds($cateIdList) {
        return Category::find()->where(['parent' => $cateIdList])->all();
    }

    /**
     * 获取所有以$cateId为父节点的分类条目
     */
    public static function getChildrenCategory($cateId) {
        $cateId = self::ensureMongoId($cateId);
        return Category::find()->where(['parent' => $cateId])->all();
    }

    /**
     * 获取所有顶层分类条目
     */
    public static function getAllTopCategory() {
        return Category::find()->where(['parent' => null, 'delete' => false])->all();
    }

    /**
     * 获取所有分类条目
     */
    public static function getAllCategory() {
        return Category::find()->all();
    }
}
