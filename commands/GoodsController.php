<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: GoodsController.php
 * $Id: GoodsController.php v 1.0 2015-10-29 22:46:32 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2015-11-03 22:30:00 $
 * @brief
 *
 ******************************************************************/

namespace app\commands;

use yii\console\Controller;
use app\modules\admin\logic\GoodsLogic;
use app\modules\admin\models\Goods;

/**
 * This command test goods logic functions.
 *
 */
class GoodsController extends Controller {

    /**
     * 添加一件商品
     * @param $count 一次添加的商品数量，默认为1个
     */
    public function actionAdd($count = 1) {
        for ($i = 0; $i < $count; $i++) {
            $goods = new Goods();
            $goods->name = '樱桃Cherry G80-3000 德文布局 机械键盘 LSCDE-0 白色青轴';
            $goods->category = new \MongoId("56376857d4b1b7e71b8b4567");
            $goods->price = rand(400, 500);
            $goods->otherPrice = array('京东' => rand(800, 900), '亚马逊' => rand(500, 700), '淘宝' => rand(500, 700));
            $goods->cashBack = rand(50, 150);
            $goods->buyLimit = rand(1, 20);
            $goods->count = rand(20, 100);
            $goods->status = 0;
            $goods->pics = array(
                array('thumb' => '/data/thumb/GLqgge2MzKd3EMfWdF7z.jpg', 'src' => '/data/GLqgge2MzKd3EMfWdF7z.jpg', 'primary' => true),
                array('thumb' => '/data/thumb/8byC0ZQRFaoz0OfwwvSh.jpg', 'src' => '/data/8byC0ZQRFaoz0OfwwvSh.jpg', 'primary' => false),
                array('thumb' => '/data/thumb/yDYV6rEWwhX7qVWKfkGS.jpg', 'src' => '/data/yDYV6rEWwhX7qVWKfkGS.jpg', 'primary' => false),
                array('thumb' => '/data/thumb/RQgo68tJ4kKKn5wFqThX.jpg', 'src' => '/data/RQgo68tJ4kKKn5wFqThX.jpg', 'primary' => false),
            );
            $goods->detail = '<dd class="topic-content">
                        <p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">从上世纪80年代第一把G80-3000出厂以来，CHERRY G80-3000系列键盘在市场上销售了至少有20年的时间，在电脑外设界可谓是一个奇迹。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/1/jNTRaauMPL5KVWqofzxl.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/1/jNTRaauMPL5KVWqofzxl.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 从外观上看，G80-3000延续了CHERRY一贯的简约风格。这款键盘采用经典的104美式键盘布局，更容易令人适应，从而能减少用户使用时的误操作几率。这款键盘有黑白两色。这不仅仅是颜色的区别，在材质选用上也有所不同。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/86/C7Su2VfKsGjPgY85hc6e.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/86/C7Su2VfKsGjPgY85hc6e.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 黑色G80-3000使用原厂POM键帽，它具有良好稳定的耐磨性，自润滑性等特点，制作的键帽长时间使用表面的纹理颗粒也很难磨损。而且黑色G80-3000的”F”和”J”两个按键键帽的弧度更大，没有设计盲点。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/31/lErv0JgWQEWl8Cr2NCP1.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/31/lErv0JgWQEWl8Cr2NCP1.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 白色键盘的键帽则选用了PBT材质，不易打油，颗粒感较强，CHERRY的PBT键帽无论是材质还是做工都更胜一筹。</p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 键帽均采用激光蚀刻填料工艺，保证键盘在长时间使用后也不会出现掉漆褪色现象。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/37/3qoZsBrXTg1bxXrnjHVZ.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/37/3qoZsBrXTg1bxXrnjHVZ.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 G80-3000机械键盘使用经典的CHERRY&nbsp;MX轴。MX开关可以说是键盘界的里程碑。第一款CHERRY MX开关于1987年11月7日推出，目前仍然以数亿的产量生产并广泛应用于各类机械键盘中，称得上是无可替代。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/59/UpkZ5140A9ThJM1oO1sH.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/59/UpkZ5140A9ThJM1oO1sH.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 每一个按键都有一个单独的开关控制闭和，而且内部使用黄金作为触点。使用时能够感受到按键干脆有力的反弹，让打字成为一种享受。当然，不同轴的手感也各有千秋。黑轴的触发键程短；茶轴则结合了机械键盘手感和普通键盘的便利；青轴的段落感明显，打字时伴随着清脆的敲击声；红轴则更加轻盈，无段落感。大家可以根据自己的需要和喜好进行选择。另外，MX轴的使用也大大延长了键盘寿命。一般来讲，单个按键的寿命为5000万次。</p><p align="center" _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><img width="90%" border="1" alt=" " data-original="http://img.bigshop.im/uploads/92/oLXwEWQdZYw9HXXcI45Z.jpg!642" _style="border: 1px solid rgb(0, 0, 0); vertical-align: top;" src="http://img.bigshop.im/uploads/92/oLXwEWQdZYw9HXXcI45Z.jpg!642" style="opacity: 1;"></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 从键盘侧面看去，可以发现六排键帽以不同高度、不同倾斜角度呈阶梯状排列。从人体工学的角度来讲，这种设计充分考虑到使用者的打字姿势，让手指以最合适的角度和键盘进行接触，并能让手指使用更少的运动完成相同的动作，从而长时间使用不易疲劳。这种独到的设计是CHERRY的工程师最先发明并使用的，这也被后来的各种机械键盘效仿。</p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 当然，这种舒适的手感也和无钢板设计有关。CHERRY G80-3000键盘将有定位柱有跳线的轴固定在底部6层PCB板上，结合PCB板的独特弹性和恢复力生成机械触感。这也得每一个按键都有7个固定点，从而不会有歪轴现象。</p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;"><span style="line-height: 1.42857143;">　　 值得一提的是，G80-3000键盘采用卡扣设计，依靠周身10处明扣和4处暗卡，就把整个键盘固定了。这也体现了CHERRY所贯彻的环保理念。</span></p><p _style="margin: 0px auto; padding: 16px 0px 0px; color: rgb(102, 102, 102); line-height: 28px; width: 570px; font-family: Arial; white-space: normal;">　　 标准的键位设计、出色的做工、缜密的细节处理使得这一款键盘被称为经典。可以看到，G80-3000系列在市场上风行20年后依旧受欢迎，新锐作家蒋方舟、锤子科技创始人罗永浩都是它的粉丝。</p><p><br></p>
                        </dd>';
            $goods->recomIndex = rand(100, 200);
            $goods->recomReason = '最经典型号 原厂最高品质 德语键盘布局 电脑设置英语键盘布局';
            $goods->createTime = rand(NOW, NOW - 10000);
            $goods->publishTime = rand(NOW, NOW - 10000) - 365 * 24 * 60 * 60;
            $goods->save();
        }
        echo 'success' . "\n";
    }

    /**
     * 输出商品详细信息
     * @param $id 商品id
     */
    public function actionDetail($id) {
        print_r(GoodsLogic::getGoodsById($id)->getAttributes());
    }

    /**
     * 输出某一个分类的总页数
     * @param category 商品分类，默认为全部
     * @param pageNum 一页商品个数，默认为20
     */
    public function actionTotalPageCount($key='',$category = null, $pageNum = 20) {
        echo GoodsLogic::totalPageCount($key,$category, $pageNum);
        echo "\n";
    }

    /**
     * 输出商品列表
     * @param key 模糊查询关键字
     * @param category 商品分类，默认为全部
     * @param page 从第几页开始，默认从第一页开始
     * @param order 排序类别，默认为1，表示按推荐指数排序
     * @param pageNum 一页商品个数，默认为20
     */
    public function actionList($key = '', $category = null, $page = 1, $order = 1, $pageNum = 1) {
        print_r(GoodsLogic::getGoodsList($key, $category, $page, $order, $pageNum));
    }
}
