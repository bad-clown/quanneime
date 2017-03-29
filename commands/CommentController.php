<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CommentController.php
 * $Id: CommentController.php v 1.0 2015-11-02 21:02:01 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 09:57:05 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\CommentLogic;

/**
 * This command test comment logic functions.
 *
 */
class CommentController extends Controller {

    /**
     * 获取一个商品的评论
     * @param @goodsId 商品id
     * @param @page 第几页，默认第1页
     * @param @pageNum 一页几条评论，默认20条
     */
    public function actionGoodsComment($goodsId, $page = 1, $pageNum = 20) {
        $comments = CommentLogic::getGoodsComment($goodsId, $page, $pageNum);
        print_r($comments);
    }

    public function actionGoodsCommentCount($goodsId) {
        $commentsCount = CommentLogic::getGoodsCommentCount($goodsId);
        print_r($commentsCount);
    }

    /**
     * 给一条评论点赞
     * @param $commentId 评论id
     */
    public function actionSupport($commentId) {
        echo CommentLogic::supportComment($commentId);
        echo "\n";
    }

    /**
     * 添加一条评论
     * @param goodsId 商品id
     * @param content 评论内容
     */
    public function actionAdd($goodsId, $content) {
        echo CommentLogic::addGoodsComment($goodsId, $content);
        echo "\n";
    }
}
