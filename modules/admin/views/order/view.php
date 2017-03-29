<?php

/* @var $this yii\web\View */
/* @var $order app\modules\admin\models\Order */

use \yii\helpers\Url;
use \yii\helpers\Html;
use app\modules\admin\models\Dictionary;
use app\components\I18n;
use yii\bootstrap\BootstrapPluginAsset;

$statusNames = [];
$statuses = Dictionary::indexMap("OrderStatus");
$statusTexts = Dictionary::indexMap("OrderStatusText");
foreach ($statuses as $k=>$s) {
    $statusNames[$s] = $statusTexts[$k];
}
BootstrapPluginAsset::register($this);
?>

<?php $this->beginBlock("topcode");  ?>
<style type="text/css" media="screen">
.p20 hr{margin:5px 0;}
</style>
<?php $this->endBlock();  ?>
<div class="p20">

<div class="order-view">
    <p>
        <?= Html::a(I18n::text('Back'), ['index',"sort"=>"-time"], ['class' => 'btn btn-default']) ?>
            <?php if($order["status"]==200){?>
        <?= Html::button(I18n::text('发货'), ['class' => 'btn btn-info',"id"=>"startDeliver","data-toggle"=>"modal","data-target"=>"#deliverdlg"]) ?>
            <?php }   ?>
            <?php if($order["status"]==200 || $order["status"]==100 || $order["status"]==150){?>
        <?= Html::button(I18n::text('取消订单'), ['class' => 'btn btn-danger',"id"=>"btncancel","data-toggle"=>"modal","data-target"=>"#canceldlg"]) ?>
            <?php }   ?>
    </p>

</div>
<div class="panel panel-warning">
<div class="panel-heading">
<div class="h3">订单&nbsp;<?=$order["orderId"]?>&nbsp;</div>
<p class="h4">当前订单状态:<b><?=$statusNames[ $order["status"] ]?></b></p>
</div>
</div>
<h4 class="p20">订单商品</h4>
<div class="clearfix">
<div class="col-xs-6"> 商品名称</div>
<div class="col-xs-2 text-center">单价</div>
<div class="col-xs-2 text-center">数量</div>
<div class="col-xs-2 text-center ">小计</div>
</div>
<hr/>
<?php 
for ( $i = 0; $i < count($order->goodsList); $i++ ) {
    $item  = $order->goodsList[$i];
?>
    <div class="clearfix item" data-itemid='<?=(string)$item["_id"]?>' >
            <div class="col-xs-6" >
                <h5>
                  <a href="<?=Url::to(["/web/item","id"=>(string)$item["goodsId"]])?>"><?=$item["name"]?></a>
                </h5>
            <div class="small" >
                <span>返现</span>
                &nbsp;
                <?php 
                    if($item["cashBack"]!=null && intval($item["cashBack"])>0){
                ?>
                ￥<span><?=$item["cashBack"]?></span>
                <?php
                    }else{
                ?>
                <span>无</span>
                <?php
                    }
                ?>
            </div>

      </div>
<div class="col-xs-2">
          <div class="text-center"> <span class="">￥<?=$item["price"]?></span> </div>
      </div>
<div class="col-xs-2 text-center">
          <div class=""> <span class=""><?=$item["count"]?>  </div>
      </div>
        <div class="col-xs-2 text-center">
            ￥<?=($item["price"])*$item["count"]?>
        </div>


    </div><!-- end item -->
    <hr/>
<?php
}
?>

<div class="clearfix">
<div class="col-xs-6"></div>
<div class="col-xs-2"></div>
<div class="col-xs-2"></div>
<div class="col-xs-2 text-center">
    <div class="h4">共计<span class="color-price ">￥<?=$order->totalMoney?></span></div>
</div>
</div>
<h4 class="p20">历史状态</h4>
<div class="p20">
<?php
for ( $i =  count($order["statusRecord"])-1;$i>=0; $i-- ) {
    $s = $order["statusRecord"][$i];
?>
<div class=""> 
<?=\Yii::$app->formatter->asDateTime($s[1])?>&nbsp;<?= $statusNames[$s[0]]?>
<?php  if ( count($s)>2 ) { ?>
&nbsp;操作用户：<?=$s[2]?>,备注<?= count($s)>3? Html::encode( $s[3] ):""?>
<?php } ?>
<hr/>
 </div>

<?php } ?>
</div>

<?php  if($order["receiverName"]!=null){ ?>
<h4 class="p20">收货地址</h4>
<div class="p20">
    <p>收货人姓名：<span id="receiverName"><?=$order["receiverName"]?></span></p>
    <p>收货地址：<span id="receiverAddr"><?=$order["receiverAddr"]?></span></p>
    <p>联系电话：<span id="receiverPhone"><?=$order["receiverPhone"]?></span></p>
</div>
<?php } ?>

<?php  if($order["paid"]){ ?>
<h4 class="p20">付款信息</h4>
<div class="p20">
    <p> 付款金额：<span class="color-price">￥<?=$order["totalMoney"]?></span></p>
    <p class="clearfix"><span class="pull-left">支付渠道：</span><a href="javascript:;" class="pull-left channel channel-<?= $order["channel"] ?>" ></a></p>
    <p>支付时间：<?=\Yii::$app->formatter->asDateTime($order["paidTime"])?></p>
</div>
<?php } ?>

<?php  if(intval($order["status"])>=300){ ?>
<h4 class="p20">发货信息</h4>
<div class="p20">
    <p>快递公司：<?=Dictionary::indexKeyValue("ExpressCompany",$order->expressComp)?></p>
    <p>快递单号：<?=$order->expressId?></p>
    <p>物流信息：<a href="<?=Dictionary::indexKeyValue("ExpressUrl",$order->expressComp)?>" target="_blank"><?=Dictionary::indexKeyValue("ExpressCompany",$order->expressComp)?></a></p>
</div>
<?php } ?>
</div>


<!-- Modal -->
<div class="modal fade" id="deliverdlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="deliverdlgLabel">订单<?=$order->orderId?>发货</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="formdeliver">
        <input type="hidden" name="id"  value="<?=(string)$order->_id?>" />
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">快递公司</label>
    <div class="col-sm-9">
        <?= Html::dropdownList("expressComp","",Dictionary::indexMap("ExpressCompany"),["prompt"=>I18n::text("None"),"class"=>"form-control","id"=>"expressComp"]) ?>
    </div>
    <div class="help-block"></div>
  </div>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">快递单号</label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="expressId" name="expressId" />
    </div>
    <div class="help-block"></div>
  </div>
  <div class="form-actions text-center">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary btnsave" id="btnconfirm">确认</button>
  </div>
</form>
      </div>
    </div>
  </div>
</div>


<!-- Modal -->
<div class="modal fade" id="canceldlg" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="deliverdlgLabel">取消订单<?=$order->orderId?></h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" id="formcancel">
        <input type="hidden" name="id"  value="<?=(string)$order->_id?>" />
<?php  
    if($order["status"]==200){
?>
<div class="alert alert-danger hide">用户已经付款，取消订单需要手动退款</div>
<?php
    }
?>
  <div class="form-group">
    <label for="inputEmail3" class="col-sm-3 control-label">取消原因</label>
    <div class="col-sm-9">
        <textarea name="comment" id="comment" class="form-control" rows="6"></textarea>
    </div>
    <div class="help-block"></div>
  </div>
  <div class="form-actions text-center">
        <button type="submit" class="btn btn-danger btncancel" id="btnconfirmcancel">确定取消订单</button>
  </div>
</form>
      </div>
    </div>
  </div>
</div>
<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    $("#btnconfirm").click(function(e){
        $("#formdeliver .has-error").removeClass("has-error");
        if($("#expressComp").val()==""){
            $("#expressComp").parents(".form-group:eq(0)").addClass("has-error");
            e.preventDefault();
            return ;
        }
        if($("#expressId").val()==""){
            $("#expressId").parents(".form-group:eq(0)").addClass("has-error");
            e.preventDefault();
            return ;
        }
        $("#btnconfirm").prop("disabled",true);
        $.ajax({
            url:"<?= Url::to(["/admin/order/deliver"]) ?>",
            type:"post",
            data:$("#formdeliver").serialize(),
            success:function(){
                window.location.reload();
            }
        });
        e.preventDefault();
    });
    $("#btnconfirmcancel").click(function(e){
        $("#formcancel .has-error").removeClass("has-error");
        if($("#comment").val()==""){
            $("#comment").parents(".form-group:eq(0)").addClass("has-error");
            e.preventDefault();
            return ;
        }
        $("#btnconfirmcancel").prop("disabled",true);
        $.ajax({
            url:"<?= Url::to(["/admin/order/cancel"]) ?>",
            type:"post",
            data:$("#formcancel").serialize(),
            success:function(){
                window.location.reload();
            }
        });
        e.preventDefault();
    });
});
</script>
<?php $this->endBlock();  ?>
