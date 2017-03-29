<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: modules/web/views/item/index.php
 * $Id: modules/web/views/item/index.php v 1.0 2015-10-30 09:49:51 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-23 21:59:03 $
 * @brief
 *
 ******************************************************************/
?>
<?php  
use \yii\helpers\Url;
use \yii\helpers\Html;
use \yii\helpers\Json;
use app\modules\admin\models\Dictionary;
?>

<?php  if($notexists){ ?>
<p class="p20 text-center h3"> 商品不存在或已经删除 </p>
<?php }else{ ?>

<div class="web-topic" id="webItem">

<div class="web-right web-item-right">


</div>
<div class="web-left web-item-left clearfix ">
    <div class="header-breadcrumb">
      <span>
        <a href="<?=Url::home()?>">首页</a> 》 <?=$item->name?>
      </span>
    </div>
    <div class="web-first-block clearfix">
       <h1 class="title"> <?=$item->name?> </h1>
      <div class="item-image pull-left" id="itemimage">
<?php
/*$pic=[
    ["thumb"=>"http://img11.360buyimg.com/n7/jfs/t1297/36/300283399/70352/49466a15/556539c6N570b3bc9.jpg","src"=>"http://img11.360buyimg.com//n0/jfs/t1297/36/300283399/70352/49466a15/556539c6N570b3bc9.jpg","primary"=>true],
    ["thumb"=>"http://img11.360buyimg.com/n7/jfs/t1270/246/1076044366/120025/3d6a9ae3/556d64fcNf28f90d0.jpg","src"=>"http://img11.360buyimg.com//n0/jfs/t1270/246/1076044366/120025/3d6a9ae3/556d64fcNf28f90d0.jpg","primary"=>false],
    ["thumb"=>"http://img11.360buyimg.com/n7/jfs/t1069/42/696152104/103033/e983cf09/554030c4Nbf430a69.jpg","src"=>"http://img11.360buyimg.com//n0/jfs/t1069/42/696152104/103033/e983cf09/554030c4Nbf430a69.jpg","primary"=>false],
    ];*/
$pic = $item->pics;
?>

        <div class="imagec easyzoom easyzoom--adjacent" id="z"><a href="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$pic[0]["src"]?>" ><img src="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$pic[0]["thumb"]?>"/></a></div>
        <div class="imagelist clearfix">
<?php
for ( $i = 0; $i < count($pic); $i++ ) {
?>
            <a href="javascript:;" class="<?=$i==0?"current":""?>" data-imgsrc="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$pic[$i]["src"]?>"><img src="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$pic[$i]["thumb"]?>"/></a> 
<?php
}
?>
        </div>
<?php
for ( $i = 0; $i < count($pic); $i++ ) {
?>
            <img class="hide" src="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$pic[$i]["src"]?>"/>
<?php
}
?>



        <div class="hide">
        <button id="wantIt" class="web-topic-btn"><span class="glyphicon glyphicon-map-marker" id="login" aria-hidden="true"></span> 想买</button>&nbsp;
        <button id="favourIt" class="web-topic-btn"><span class="glyphicon glyphicon-heart" id="login" aria-hidden="true"></span> 关注</button>&nbsp;
        </div>


      </div><!-- end .item-image -->
      <div class="web-pricebox clearfix pull-right">

        <div>
          <p class="web-item-info" style="font-size:14px">
          <span style="font-size:16px;font-weight:bold;color:#D0021B"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span></span> 
            推荐指数 
            <span style="font-size:20px;font-weight:bold;color:#D0021B">
            <?=$this->render("@app/modules/web/views/_star",["star"=>$item->recomIndex])?>
            <span style="font-size:14px"></span></span><!--&nbsp;/&nbsp;
            <span id="want" style="font-weight:bold">34</span>人想买
            /
              <span id="favour" style="font-weight:bold">14</span>关注
            /
              <span id="twitterCount" style="font-weight:bold">14</span>冒泡 -->
          </p>
            <p class="web-item-info">
            <nobr> <?= preg_replace("/[\s]+/","</nobr><nobr>",trim($item->recomReason)) ?> </nobr>
            </p>
        <div class="subgoods" id="subgoods">
            <?php  foreach($subgoods["features"] as $f){  ?>
            <div class="clearfix sgfeature m10tb">
                <div class="col-xs-3 p0 "><?=$f["name"]?>：</div>
                <div class="col-xs-9">
                    <?php asort($f["values"]);  ?>
                    <?php  foreach($f["values"] as $v){  ?>
                        <a href="javascript:;" class=" <?= in_array($v, explode(",", $item->featureKey))?"active":""  ?> "><?=$v?></a>
                    <?php }  ?>
                </div>
            </div>
            <?php  } ?>
        </div>
        <div class="cart-hover">
          <div class="clearfix">
        <div class="price pull-left"> 
<?php
if(\Yii::$app->user->isGuest ||  \Yii::$app->user->identity->vipLevel=='VIP0'){
?>
            <span class="web-sale-price ">￥<?=$item->price+intval($item->vipPrice['VIP0'])?></span>
<?php }else{?>
<?=\Yii::$app->user->identity->vipLevel?>价格：
            <span class="web-sale-price ">￥<?= $item->price+intval($item->vipPrice[\Yii::$app->user->identity->vipLevel]) ?></span>

<?php } ?>
        </div>
                <?php  $userBuyLimit = min($item->count,max(0,$item->buyLimit - $item->buyCount - $item->cartCount));  ?>
                <?php  if($userBuyLimit >0 && (  (!is_array($item->require)) || ( (!\Yii::$app->user->isGuest) &&  in_array(\Yii::$app->user->identity->vipLevel,$item->require)) ) ){?>
                <div class="pull-left item-numberc">
                <div class="input-group pull-left item-number " data-type="number" <?php if($userBuyLimit>0){  ?>  data-max="<?=min($item->count,$userBuyLimit)?>" <?php  } ?>  >
                        <span class="input-group-addon minus">
                          <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </span>
                        <input type="text" class="form-control number input-sm text-center" value="1" id="itemCount" >
                        <span class="input-group-addon plus">
                          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </span>
                  </div>
                <a class="web-add-cart web-tip add-cart" id="add-cart-btn" tabindex="0" role="button" href="<?=Url::to(["@m/cart/add-to-cart","id"=>$id,"count"=>"1"])?>">购买</a>
              </div>
                <?php }  ?>
      </div>
            </div>
            <div class="item-notice"> 
                <p> <?php if($item->count>0){?> 库存仅剩<?=$item->count?>件  <?php }else{ ?> 该商品已售罄 <?php }   ?> </p>
                <p><?php if($item->cashBack>0){  ?>  下单立即返现￥<?=$item->cashBack?> <?php  } ?> </p>
                <p><?php if($item->buyLimit>0){  ?>  最多购买<?=$item->buyLimit?>件 <?php  } ?>
                <?php  if(  (!is_array($item->require)) || ((!\Yii::$app->user->isGuest) &&  in_array(\Yii::$app->user->identity->vipLevel,$item->require)) ){  ?>
                <?php if($userBuyLimit>0){  ?>  您还能购买<?=$userBuyLimit?>件 <?php  }else{ ?>您不能再购买该商品<?php } ?> </p>
                <?php  }else{ ?>
                <p> <?= implode(",",$item->require)  ?>可以购买 </p>
                <?php  } ?>

                <p><a href="<?=Dictionary::indexKeyValue("Help","LimitHelpUrl")?>">限购后如何拿货？</a></p>
            </div>
            <?php  if(count($item->otherPrice)>0){ ?>
            <h6>其他平台报价  </h6>
            <table class="table table-condensed table-otherprice">
                <tbody>
                <?php  foreach ($item->otherPrice as $k=>$p) { ?>
                    <tr><td><?=$k?></td><td>￥<?=$p?></td></tr>
                <?php }   ?>
                </tbody>
            </table> 
            <?php }   ?>
        </div>
      </div>

    </div>

      <div class="clearfix item-detail"><?= $item["detail"] ?></div>

    <div class="web-item-block hide" style="position:relative;padding-top:60px;">
            <div class="input-group" style="width:100%;margin-bottom:20px">
        <textarea id="twitter" style="width:70%;padding:10px;line-height:24px;resize:none;position:relative;border:2px solid #ddd" rows="1" placeholder="我来说两句 (140字)"></textarea>
        <button id="submitTwitter" class="web-topic-btn" style="position:absolute;top:0;height:48px"><span class="glyphicon glyphicon-check" id="login" aria-hidden="true"></span> 发布</button>
      </div>

            <dl class="item-twitter">
<?php for ( $i = 0; $i < count($comments); $i++ ) {
    $c = $comments[$i];
?>
        <dt class="clearfix">
        <button class="twitterUseful useful-btn pull-right" data-id="<?=(string)$c["_id"]?>"  data-toggle="tooltip" data-placement="top" title="认为有用"><span><?=$c["helpful"]?></span> <span class="glyphicon glyphicon-chevron-up" aria-hidden="true"></span></button>
         <span class="user-name"><?= empty( $c["userId"]) ?"热心网友":$c["userId"]?></span> 
        <span class="gray"><?= \Yii::$app->formatter->asDateTime( $c["publishTime"],"php:Y-m-d H:i:s")?></span>
        </dt>
        <dd style="margin-bottom:15px;border-bottom:1px solid #EEE;padding-bottom:5px">
          <span class="quote">“ </span><?=Html::encode($c["content"])?><span class="quote"> ”</span>
        </dd>
<?php
} ?>
              </dl>
<?=$this->render("@app/modules/web/views/_page",["page"=>$page,"pageCount"=>$commentPageCount])?>
    </div>





</div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    $("#itemCount").change(function(){
        var $btn = $("#add-cart-btn");
        $btn.attr("href",$btn.attr("href").replace(/count\=\d+/,"count="+$(this).val()));
    });
    $(document.body).on("click",".useful-btn[data-id]",function(){
        var $btn =$(this),$s = $btn.find("span:eq(0)");
        $.getJSON("<?= Url::to(["/web/item/support","commentId"=>""]) ?>"+$btn.attr("data-id")).then(function(result){
            if(result.status){
                $s.html(parseInt($s.html(),10)+1);
            }
        });
    }).on("click","#submitTwitter",function(){
        var $t = $("#twitter");
        if($t.val()=="") return ;
        $.ajax({type:"post",url:"<?= Url::to(["comment","goodsId"=>$id]) ?>",data:{content:$t.val().substr(0,140)}}).then(function(result){
            if(result.status){
                window.location.href="<?= Url::to(["","page"=>1,"id"=>$id]) ?>&_="+(new Date()).getTime()+"#t";
            }
        });
    });
    $("#itemimage").on("mouseenter",".imagelist>a",function(){
        var $thumbimg = $(this).find("img"),$z = $("#z"),zoom = $z.data("easyZoom");
        $("#itemimage .current").removeClass("current");
        $(this).addClass("current");
        $("#itemimage .imagec img").attr("src",$thumbimg.attr("src"));
        $("#itemimage .imagec a").attr("href",$thumbimg.parent().attr("data-imgsrc"));
        zoom && (zoom.teardown());
        $z.easyZoom();
    });
    $("#z").easyZoom();
    var subgoods = <?= Json::encode($subgoods["items"]) ?>;
    $("#subgoods").on("click","a",function(){
        var k="",$this = $(this);
        if($this.is(".active")) return ;
        $this.parent().children("a").removeClass("active");
        $this.addClass("active");
        k = $("#subgoods .active").map(function(){return this.innerHTML;}).toArray().join(",");
        window.location.href="<?= Url::to(["@m/item","id"=>""]) ?>"+subgoods[k].id;
    });
});
</script>
<?php $this->endBlock();  ?>
<?php } ?>
