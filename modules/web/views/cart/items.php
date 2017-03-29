<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:   */

/*******************************************************************
 * @File: modules@m/views/index/index.php
 * $Id: modules@m/views/index/index.php v 1.0 2015-11-04 21:42:56 maxing $
 * $Author: maxing xm.crazyboy@gmail.com $
 * $Last modified: 2016-01-24 10:25:43 $
 * @brief
 *
 ******************************************************************/
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\Json;
use app\modules\admin\models\Dictionary;

?>
<div class="cart-items" id="cart-items">
<?php  if($this->context->action->id=="add-to-cart"){ ?>
<div class="alert alert-success text-center" role="alert">
添加成功！<a href="<?= Url::home() ?>">此处</a>继续购物
</div>
<?php } ?>
<?php
if(\Yii::$app->user->isGuest){
    $vipLevel = 'VIP0';
}else{
    $vipLevel = \Yii::$app->user->identity->vipLevel;
} ?>

<div id="cartitems"></div>
<script type="text/x-tmpl" id="tplitems">
{% var o = p.items;%}

<div class="clearfix">
<div class="col-xs-5"> <h4> 我的购物车</h4></div>
<div class="col-xs-5"></div>
<div class="col-xs-2 text-center sumheader"> 
{%  if(o.length>0){
%}
合计 
{% }  %}

</div>
</div>

{%  if(o.length>0){
var checkedLen= 0;
for(var i=0,l=o.length;i<l;i++){
    if(o[i].checked){
        checkedLen++;
    }
}
%}
<div class="clearfix">
<div class="col-xs-1">
<label><input  type="checkbox" class="cball" {%= checkedLen==o.length?"checked":"" %}/>全选</label>
</div>
<div class="col-xs-11"></div>
</div>
<hr/>
{% }  %}

{%  
for (var  $i = 0; $i < o.length; $i++ ) {
    var $item = o[$i];
%}

<div class="clearfix item" data-itemid='{%= $item["_id"]["$id"]%}' data-idx="{%=$i%}" >
    <div class="col-xs-1">
        <input type="checkbox" class="cbitem" name="items" {%=$item.checked?"checked":""%} value="{%= $item["_id"]["$id"]%}" />
    </div>
    <div class="col-xs-4">
        <a class="pic" href="<?=Url::to(["@m/item","id"=>""])?>{%= $item["_id"]["$id"]%}">
        <img src="<?=Dictionary::indexKeyValue("App","ImageServer")?>{%= $item["pics"][0]["thumb"] %}" class="lazy">
      </a>
    </div>
            <div class="col-xs-5" >
                <h5>
                  <a href="<?=Url::to(["@m/item","id"=>""])?>{%= $item["_id"]["$id"]%}">{%=$item["name"]%}</a>
                </h5>
            <p class="small" >
                <span>返现</span>
                &nbsp;
                {%
                    if(!isNaN($item.cashBack)){
                %}
                ￥<span>{%= $item["cashBack"] %}</span>
                {%
                    }else{
                %}
                <span>无</span>
                {%
                    }
                %}
            </p>
        <div class="clearfix">
          <div class="price pull-left"> <span class="web-sale-price ">￥{%= parseInt($item["price"],10) + parseInt(($item['vipPrice']||{})['<?= $vipLevel ?>']||0,10) %}</span>  </div>

                <div class="pull-left item-numberc">
                <div class="input-group pull-left item-number " data-type="number" data-max="{%=Math.min(Math.max(0,parseInt($item["buyLimit"],10)-parseInt($item["buyCount"],10)),$item['countCount'])%}">
                        <span class="input-group-addon minus">
                          <span class="glyphicon glyphicon-minus" aria-hidden="true"></span>
                        </span>
                        <input type="text" class="form-control number input-sm text-center" value="{%=$item["count"]%}">
                        <span class="input-group-addon plus">
                          <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        </span>
                  </div>
                  <a href="javascript:;" class="delcartitem" data-html="true" data-toggle="popover" data-trigger=" focus" data-placement="top"
                        data-content="<a href='javascript:;' class='btn btn-primary confirmdelcartitem' data-id='{%= $item["_id"]["$id"]%}'>确认</a>&nbsp;&nbsp;<a href='javascript:;' class='btn btn-default'>取消</a>" title="确认删除?"
                    >删除</a>
              </div>
      </div>

      </div>
        <div class="col-xs-2 text-center">
            <div class="itemsum">
            ￥{%= ($item["price"]+ parseInt(($item['vipPrice']||{})['<?= $vipLevel ?>']||0,10)) *$item["count"]%}
            </div>
        </div>


    </div><!-- end item -->
    <hr/>
{% } %}
{% if(!o.length){ %}
<div class="text-center" style="padding:80px;">购物车是空的</div>
{% }else{ %} 
<div class="clearfix">
    <div class="xs-col-6">
        <label><input  type="checkbox" class="cball" {%= checkedLen==o.length?"checked":"" %}/>全选</label>
        &emsp;
        <a href="javascript:;" class="btndelmitems"> 删除所选商品</a>

    </div>
    <div clsss="xs-col-6 text-right">
        <p class="h3 text-right">共计<span class="color-price ">￥
        {%
            var totalMoney =0;
        for(var i =0,l=o.length;i<l;i++){o[i].checked && (totalMoney+=((o[i].price+ parseInt((o[i]['vipPrice']||{})['<?= $vipLevel ?>']||0,10))*o[i].count))}
        %}
        {%=totalMoney%}
        </span></p>
        <p class="text-right">
        <button class="btn btn-danger btn-lg btnsubmitcart" {%= checkedLen==0?"disabled":"" %}> 提交订单 </button>
         </p>
    </div>
</div>
{% } %}
</script>



</div>


<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    tmpl.arg = "p";
    var fn = tmpl("tplitems"),$cart = $("#cartitems"),loadData = function(items){
        $('#cart-items [data-toggle="popover"]').popover("destroy");
        $cart.html(fn(items));
        $('#cart-items [data-toggle="popover"]').popover();
    },data = {"items":<?=  Json::encode($items) ?>};
    $.each(data.items,function(i,row){row.checked =true;});
    loadData(data);
    $cart.on("change",".cball",function(){
        var c = this.checked;
        for(var i = 0,l=data.items.length;i<l;i++){
            data.items[i].checked = c;
        }
        loadData(data);
    }).on("change",".cbitem",function(){
        var idx = parseInt($(this).parents(".item").attr("data-idx"),10);
        data.items[idx].checked = this.checked;
        loadData(data);
    }).on("change",".number",function(){
        var idx = parseInt($(this).parents(".item").attr("data-idx"),10);
        data.items[idx].count = this.value;
        loadData(data);
        $.getJSON("<?= Url::to(["@m/cart/update-item-count","id"=>""]) ?>"+data.items[idx]["_id"]["$id"]+"&count="+this.value).then(function(){
        });
    }).on("click",".confirmdelcartitem",function(){
        var idx = parseInt($(this).parents(".item").attr("data-idx"),10);
        $.getJSON("<?= Url::to(["@m/cart/del-item","id"=>""]) ?>"+data.items[idx]["_id"]["$id"]).then(function(){
            data.items.splice(idx,1);
            loadData(data);
            $(document.body).trigger("updatecart");
        });
    }).on("click",".btndelmitems",function(){
        var l = data.items.length,arr =[];
        while(l--){
            if(data.items[l].checked){
                arr.push(data.items[l]["_id"]["$id"]);
                data.items.splice(l,1);
            }
        }
        $.getJSON("<?= Url::to(["@m/cart/del-item","id"=>""]) ?>"+arr.join(",")).then(function(){
            loadData(data);
            $(document.body).trigger("updatecart");
        });
    }).on("click",".btnsubmitcart",function(){
        $btn = $(this),arr=[];
        $btn.html("正在提交。。。").attr("disabled",true);
        $.each(data.items,function(i,item){
            item.checked && (arr.push({id:item["_id"]["$id"],count:item.count}));
        });
        $.ajax({type:"post",url:"<?= Url::to(["@m/cart/submit-order"]) ?>",dataType:"json",data:{data:JSON.stringify(arr)}}).then(function(res){
            console.log(res);
        });
    })
});
</script>
<?php $this->endBlock();  ?>
