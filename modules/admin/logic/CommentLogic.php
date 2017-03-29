<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: CommentLogic.php
 * $Id: CommentLogic.php v 1.0 2015-11-02 20:06:43 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 11:25:30 $
 * @brief
 *
 ******************************************************************/

namespace app\modules\admin\logic;

use Yii;
use app\components\BaseObject;
use app\modules\admin\models\Goods;
use app\modules\admin\models\Comment;

class CommentLogic extends BaseObject {
    /**
     * 添加一条评论
     */
    public static function addGoodsComment($goodsId, $content) {
        $comment = new Comment;
        $comment->goodsId = self::ensureMongoId($goodsId);
        $comment->userId = '';
        $comment->content = $content;
        $comment->helpful = 0;
        $comment->publishTime = NOW;
        $comment->delete = 0;
        $comment->save();
        return true;
    }

    /**
     * 给一条评论点赞
     */
    public static function supportComment($commentId) {
        $comment = self::getComment($commentId);
        if ($comment == null) {
            return false;
        }
        $comment->helpful += 1;
        $comment->save();
        return true;
    }

    /**
     * 根据评论id获取评论
     */
    public static function getComment($commentId) {
        return Comment::findOne(self::ensureMongoId($commentId));
    }

    /**
     * 获取商品评论
     */
    public static function getGoodsComment($goodsId, $page = 1, $pageNum = 20) {
        $goodsId = self::ensureMongoId($goodsId);
        $offset = (intval($page) - 1) * intval($pageNum);
        $query = Comment::find();
        $query = $query->select(['goodsId' => false]);
        $query = $query->where(['goodsId' => $goodsId]);
        $query = $query->offset($offset)->limit(intval($pageNum));
        $query = $query->orderBy('publishTime DESC');
        $comments = $query->all();
        $ret = array();
        $userIds = array();
        foreach ($comments as $comment) {
            $row = $comment->getAttributes();
            $row['userName'] = 'testUser';
            $userIds[] = $row['userId'];
            $ret[] = $row;
        }
        return $ret;
    }

    /**
     * 获取商品评论条数
     */
    public static function getGoodsCommentCount($goodsId) {
        $goodsId = self::ensureMongoId($goodsId);
        $query = Comment::find();
        $query = $query->select(['goodsId' => false]);
        $query = $query->where(['goodsId' => $goodsId]);
        $c = $query->count();
        return $c;
    }
}
