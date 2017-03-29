<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: modules/web/views/index/index.php
 * $Id: modules/web/views/index/index.php v 1.0 2015-11-04 21:42:56 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-21 23:46:42 $
 * @brief
 *
 ******************************************************************/
use yii\helpers\Url;
use yii\helpers\Html;
use app\modules\admin\models\Dictionary;
?>
<?php 
if(isset($key) && (!empty($key))){
?>
<p>搜索&nbsp;"<?= Html::encode($key) ?>"&nbsp;<a title="清空搜索" href="<?=Url::to(["","key"=>""])?>">×</a>  &nbsp;的结果：</p>
<?php } ?>


<?php 



if ( isset($category) ) {
?>
<div class="web-list-title">
<a href="<?=Url::to( array_merge( [""],$_GET,["c"=>""]))?>" class="<?= ($c!=null && (!empty($c)))?"":"active" ?>">全部</a>
<?php  
for ( $i = 0; $i < count($category); $i++ ) {
    $cc = $category[$i];
?>
    <a href="<?=Url::to( array_merge( [""],$_GET,["c"=>(string)$cc["_id"]]))?>" class="<?=(string)$cc["_id"]==$c?"active":""?>"><?=$cc["name"]?></a>
<?php
}
?>
</div>
<div class="web-list-title">
<?php
$orders = Dictionary::indexList("OrderNames");
for ( $i = 0; $i < count($orders); $i++ ) {
    $o = $orders[$i];
?>
<a href="<?=Url::to(array_merge([""],$_GET,["order"=>$o["key"]]))?>" class="<?= ($o["key"]==$order)?"active":"" ?>"><?=$o["value"]?></a>
<?php
}
?>
</div>
<?php } ?>
<div class="web-list">
<?php 
for ( $i = 0; $i < count($list); $i++ ) {
    $item  = $list[$i];
?>
    <div class="web-list-item clearfix">
    <a href="<?=Url::to(["/web/item","id"=>(string)$item["_id"]])?>">
        <img data-original="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$item["pics"][0]["thumb"]?>" class="pull-left lazy item-mainpic">
      </a>
            <div class="pull-left" style="width:60%;padding:30px 50px 0 40px">
                <h4 style="margin-top:0;font-weight:bold;line-height:26px">
          <a href="<?=Url::to(["/web/item","id"=>(string)$item["_id"]])?>"><?=$item->name?></a>&nbsp;
          <nobr>
<?php
if( \Yii::$app->user->isGuest ||  \Yii::$app->user->identity->vipLevel=='VIP0'){
?>
            <span style="color:#D0021B">￥<?=$item->price+intval($item->vipPrice['VIP0'])?></span>
<?php }else{?>
<?=\Yii::$app->user->identity->vipLevel?>价格：
            <span  style="color:#D0021B">￥<?= $item->price+intval($item->vipPrice[\Yii::$app->user->identity->vipLevel]) ?></span>

<?php } ?>
            </nobr>
        </h4>
        <p class="web-item-info" style="font-size:12px;color:#999;margin-bottom:25px">
          <span style="font-weight:bold">
            <span class="glyphicon glyphicon-fire" aria-hidden="true"></span>
            推荐指数
            <?=$this->render("@app/modules/web/views/_star",["star"=>$item->recomIndex])?>
          </span>

            &nbsp;
            <span>返现</span>
            <?php 
                if($item["cashBack"]!=null && intval($item["cashBack"])>0){
            ?>
            <span>￥<?=$item["cashBack"]?></span>
            <?php
                }else{
            ?>
            <span>无</span>
            <?php
                }
            ?>
        </p>
        <div class="web-item-info">
        <nobr><?= preg_replace("/[\s]+/","</nobr><nobr>",trim($item->recomReason)) ?> </nobr>
        </div>
<?php
    $comment  = $comments[(string)$item["_id"]];
    if(count($comment)>0){
        $comment = $comment[0];
?>
            <p class="small">
            <span class="user-name"><?= empty( $comment["userId"]) ?"热心网友":$comment["userId"]?></span>说:
          <span class="quote">“</span>
            <?=Html::encode($comment["content"])?>
          <span class="quote">”</span>

        </p>
<?php
    }

?>
      </div>


    </div><!-- end item -->
<?php
}
?>

<?=$this->render("@app/modules/web/views/_page",["page"=>$page,"pageCount"=>$pageCount])?>
</div>
