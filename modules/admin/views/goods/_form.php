<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Goods */
/* @var $form yii\widgets\ActiveForm */
?>
<style type="text/css">
.formgoods{margin:0 20px;}
.pics li {
    list-style-type: none;
    /*position: relative;*/
    padding: 0;
    margin-right:15px;
}
.pics li .removepic {
    /*position: absolute;
    right: -10px;
    top: -10px;*/
    margin-left:-11px;
    margin-top:-11px;
    float:right;
    background: #eee;
    width: 20px;
    height: 20px;
    text-align: center;
    line-height: 20px;
    border-radius: 50%;
    z-index: 3;
    opacity: 0.8;
}
.label-pics{width:100px;}
.form-pic{position:absolute;z-index:4;width:60px;opacity:0;}
.fgrequire .checkbox{display:inline;}
</style>
<div class="goods-form">

    <?php $form = ActiveForm::begin([
        'id' => 'goods-form',
        'options' => ['class' => 'formgoods',"autocomplete"=>"off"],
        /*'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-xs-10\">{input}</div>\n<div class=\"col-xs-2\"></div><div  class=\"col-xs-2\">{error}</div>",
            'labelOptions' => ['class' => 'col-xs-2 control-label'],
            ],*/
    ]); ?>

    <!--<?= $form->field($model, '_id',["options"=>["class"=>"hide"]])->hiddenInput()->label("") ?>-->
    <input type="hidden" name="returnUrl" value="<?= isset( $_SERVER['HTTP_REFERER'])? $_SERVER['HTTP_REFERER']:""?>" />

    <?= $form->field($model, 'name')->textInput(['maxlength' => 50]) ?>
    <?= $form->field($model, 'category')->dropDownList($categories) ?>
    <?= $form->field($model, 'price')->textInput(['maxlength' => 10,"data-type"=>"number",'class'=>"form-control number"]) ?>
<?php  
$vipList = Dictionary::indexList("VIP");
$vipNames = ArrayHelper::getColumn($vipList,"key");
asort($vipNames);
$defaultVipPrice = Dictionary::indexMap("DefaultVipPrice");
?>
    <div class="form-group">
    <label class="control-label" ><?=$model->getAttributeLabel("vipPrice")?></label>
<?php  
foreach ($vipNames as $n) {
?>
        <div class="clearfix m10tb">
        <div class="col-xs-2 text-right"><?=$n ?> </div >
        <div class="col-xs-10">
            <input type="text" class="form-control  number" name="Goods[vipPrice][<?=$n?>]" value="<?= $model->vipPrice==null?$defaultVipPrice[$n]:$model->vipPrice[$n] ?>" data-type="number">
        </div>
        </div>
<?php
}
?>
    </div>

    <div class="form-group fgrequire">
        <div class="clearfix m10tb">
            <div class="col-xs-2 text-right">
                    <label class="control-label" ><?=$model->getAttributeLabel("require")?></label>
            </div >
        <div class="col-xs-10">
            <?php  
                foreach ($vipNames as $n) {
            ?>
                <div class="checkbox">
                <label> <input type="checkbox" name="Goods[require][]"  value="<?=$n?>" <?= is_array($model->require)? ( (in_array($n,$model->require))?" checked  ":""):" checked " ?> /> <?=$n?> </label>
                </div>
            <?php } ?>
        </div>
        </div>
    </div>


    <div class="form-group">
        <label class="control-label"><?=$model->getAttributeLabel("otherPrice")?></label>
        <div class="">
<?php  
if($model["otherPrice"]==null){
    $model["otherPrice"]=[];
}
$platforms = Dictionary::indexList("OtherPlatform");
foreach ($platforms as $p) {
?>
<div class="clearfix m10tb">
<div class="col-xs-2 text-right"><?=$p["key"]?></div>
<div class="col-xs-10"><input type="text" class="form-control" name="Goods[otherPrice][<?=$p["key"]?>]" value="<?= array_key_exists($p["key"], $model["otherPrice"])? $model["otherPrice"][$p["key"]]:""  ?>"></div>
</div>
<?php
}
?>
        </div>
    </div>

    <?= $form->field($model, 'cashBack')->textInput(['maxlength' => 10]) ?>
    <?= $form->field($model, 'buyLimit')->textInput(['maxlength' => 10]) ?>
    <div class="form-group" id="goodspics">
        <label class="control-label label-pics" for=""><?=$model->getAttributeLabel("pics")?> </label>
        <a href="javascript;;" id="btnaddpic" class="hide" ><span class="glyphicon glyphicon-plus"></span>添加</a>
        <ul class="clearfix pics m10tb p0" id="pics">
<?php  
if($model["pics"]==null){
    $model["pics"]=[];
}
foreach ($model["pics"] as $i=>$p) { ?>

        <li class="pull-left">
            <a href="javascript:;" class="removepic" ><span class="glyphicon glyphicon-remove"></span></a>
            <img src="<?=Dictionary::indexKeyValue("App","ImageServer")?><?=$p["thumb"]?>" />
            <input type="hidden" name="Goods[pics][][thumb]" value="<?=$p["thumb"]?>" />
            <input type="hidden" name="Goods[pics][][src]" value="<?=$p["src"]?>" />
        </li>
<?php } ?>
        </ul>
        <div class="help-block"></div>
    </div>
    <?= $form->field($model, 'count')->textInput(['maxlength' => 10])->hint("库存为空或者为0时前端显示售罄") ?>
    <?= $form->field($model, 'recomIndex')->dropdownList([1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10]) ?>
    <?= $form->field($model, 'recomReason')->textInput()->hint("多个推荐理由以半角空格分隔") ?>
    <?= $form->field($model, 'status')->dropdownList(Dictionary::indexMap("GoodsStatus")) ?>
    <div class="form-group form-inline" id="subgoods" >
        <label class="control-label hide" >子商品</label>
        <div data-bind="foreach:selFeatures" id="subgoodsfeatures">
            <div class="m10tb">
                <div class="form-group" data-bind="css:{'has-error':name()==''}">
                    <label class="form-label">属性名称</label>
                    <input type="text" class="form-control" data-bind="value:name,attr:{name:'subgoods[features]['+$index()+'][name]'}" />
                </div>
                <div class="form-group" data-bind="css:{'has-error':/^,|,,|,$/g.test(values())||values()==''}">
                    <label class="form-label">属性可能值</label>
                    <input type="text" class="form-control" data-bind="value:values" />多个属性值以,分隔,每个属性值不能为空
                    <button class="btn btn-default" type="button" data-bind="click:$root.removeFeature">删除</button>
                </div>
                <!-- ko foreach:values().split(',') -->
                    <input type="hidden" data-bind="{value:$data,attr:{name:'subgoods[features]['+$parentContext.$index()+'][values]['+$index()+']'}}" />
                <!-- /ko -->
            </div>
        </div>
        <p>
            <button type="button" class="btn btn-default" data-bind="click:addFeature">添加属性</button>
         </p>
        <p>
            <select  data-bind="options:features,optionsText:'value',optionsValue:'name',value:selAttr" class="form-control"></select>
            <button type="button" class="btn btn-default" data-bind="click:addAttr">添加差异属性</button>
         </p>
        <div class="help-block"></div>
        <div class="alert alert-warning">属性发生变化，表格数据将被清空，重新生成行</div>
        <div class="alert alert-danger hide" data-bind="css:{hide:!$root.featureErr()}">请先修复属性错误</div>
        <table class="table table-bordered" data-bind="css:{hide:$root.featureErr() || $root.selFeatures().length==0}" id="subgoodsattr">
            <thead><tr>
                <th></th>
                <!-- ko foreach:model.attr -->
                    <th >
                        <span data-bind="text:$root.featureMapping[$data]"></span>
                        <!-- ko if:$data=='otherPrice' -->
                            (<span data-bind="text:$root.otherPlatforms().map(function(row){return row.value()}).join(',')"></span>)
                        <!-- /ko -->
                        <a href="javascript:;" data-bind="click:$root.removeAttr,css:{hide:$data=='price'}" ><span class="glyphicon glyphicon-remove"></span></a>
                    </th>
                <!-- /ko -->
            </tr></thead>
            <tbody data-bind="foreach:selFeatureRows">
                <tr>
                    <td data-bind="html:$data.replace(/\,/g,'&nbsp;')"></td>
                    <!-- ko foreach:$root.model.attr -->
                        <td>
                            <!-- ko if:$data!='otherPrice' -->
                            <input type="text" class="form-control number" data-type="number" data-bind="attr:{name:'subgoods[items]['+$parentContext.$data+']['+$data+']'},value:($root.subgoods.items[$parentContext.$data]||{})[$data]"  />
                            <!-- /ko -->

                            <!-- ko if:$data=='otherPrice' -->
                                <!-- ko foreach:$root.otherPlatforms -->
                                    <input type="text" class="form-control number" data-type="number" style="width:70px;display:inline-block;" data-bind="attr:{name:'subgoods[items]['+$parents[1]+']['+$parents[0]+']['+key()+']'},value:(($root.subgoods.items[$parents[1]]||{})[$parents[0]]||{})[key()]"  />
                                <!-- /ko -->
                            <!-- /ko -->
                        </td>
                    <!-- /ko -->
                </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <p> <label class="control-label"><?=$model->getAttributeLabel("detail")?></label> </p>
        <script type="text/plain" id="detail" name="Goods[detail]">
            <?= $model["detail"] ?>
        </script>
    </div>

    <div class="form-group m10tb">
        <div class="col-xs-2"></div>
        <div class="col-xs-10">
            <?= Html::a(I18n::text('Cancel'), ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton($model->isNewRecord ? I18n::text('Create') : I18n::text('Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<form method="post" id="form-pic" class="form-pic" action="<?= Dictionary::indexKeyValue("App","ImageServer") ?>/image/image/upload-goods-pic" enctype="multipart/form-data" target="uploadframe" >
    <input type="file" name="pic" id="pic" onchange="this.parentNode.submit();" />
</form>
<iframe src="javascript:;" name="uploadframe" id="uploadframe" class="hide" ></iframe>

<?php $this->beginBlock('bottomcode'); ?>

<script  type="text/javascript" src="<?= Url::to(["@web/static/js/html.sortable.min.js"]) ?>"></script>
<script  type="text/javascript" src="<?= Url::to(["@web/static/ueditor/ueditor.config.js"]) ?>"></script>
<script  type="text/javascript" src="<?= Url::to(["@web/static/ueditor/ueditor.all.js"]) ?>"></script>
<script  type="text/javascript" src="<?= Url::to(["@web/static/js/knockout.js"]) ?>"></script>
<script  type="text/javascript" src="<?= Url::to(["@web/static/js/knockout.viewmodel.min.js"]) ?>"></script>
<script type="text/javascript">
$(function(){
    var ue =new UE.getEditor("detail"),
        vm  = ko.viewmodel.fromModel({
            model:$.extend({attr:[]},<?= Json::encode($model->toArray(['attr'])) ?>),
            otherPlatforms:<?= Json::encode(Dictionary::indexList("OtherPlatform"))  ?>,
            features:[
                {name:'price',value:"<?= $model->getAttributeLabel("price") ?>"},
                {name:'buyLimit',value:"<?= $model->getAttributeLabel("buyLimit") ?>"},
                {name:'cashBack',value:"<?= $model->getAttributeLabel("cashBack") ?>"},
                {name:'count',value:"<?= $model->getAttributeLabel("count") ?>"},
                {name:'otherPrice',value:"<?= $model->getAttributeLabel("otherPrice") ?>"}
            ],
            selFeatures:[],
            selAttr:"",
            selFeatureRows:[],
            featureErr:false,
            subgoods:<?= Json::encode($subgoods) ?>
        }),arrFeatures=[];
    ko.utils.arrayForEach(vm.subgoods.features(),function(f){
        f.values  =ko.observable(f.values().join(","));
    });
    vm.selFeatures( vm.subgoods.features());
    vm.featureMapping={};
    ko.utils.arrayForEach(vm.features(),function(f){
        vm.featureMapping[f.name()] = f.value();

    })
    if(!vm.model.attr().length){
        vm.model.attr.push("price"); //价格必须字段
    }
    $.extend(vm,{
        addFeature:function(){
            var newfeature  = ko.viewmodel.fromModel({name:"",values:""}),me= this;
            newfeature.values.subscribe(me.featureRows);
            vm.selFeatures.push(newfeature);
            vm.featureRows();
        },
        removeFeature:function(model,e){
            vm.selFeatures.remove(model);
            vm.featureRows();
        },
        addAttr:function(){
            if(ko.utils.arrayIndexOf(vm.model.attr(),vm.selAttr())==-1){
                vm.model.attr.push(vm.selAttr());
            }
        },
        removeAttr:function(model,e){
            vm.model.attr.remove(model);
        },
        featureRows:function(){
            var arrValues=[];
            ko.utils.arrayForEach(vm.selFeatures(),function(sf){
                var arr = ko.utils.arrayFilter(sf.values().split(","),function(v){return v!="";});
                if(arrValues.length==0){
                    arrValues = arr;
                }else{
                    var newArrValues=[];
                    ko.utils.arrayForEach(arr,function(vv){
                        ko.utils.arrayForEach(arrValues,function(v){
                            newArrValues.push(v+","+vv);
                        });
                    });
                    arrValues = newArrValues;
                }
            });
            vm.selFeatureRows(arrValues);
            setTimeout(function(){
                vm.featureErr($("#subgoodsfeatures .has-error").length>0);
            },0);
        }
    });
    vm.featureRows();
    ko.utils.arrayForEach(vm.selFeatures(),function(f){
        f.values.subscribe(vm.featureRows);
    });
    vm.selFeatures( vm.subgoods.features());
    ko.applyBindings(vm,document.getElementById("goods-form"));
    $("#btnaddpic").hover(function(){
        var $this = $(this),$f = $("#form-pic");
        $f.css({top:$this.offset().top,left:$this.offset().left});
    });
    $(window).on("message",function(e){
        var $pics = $("#pics");
        e = e.originalEvent;
        if(e.origin=="<?= Dictionary::indexKeyValue("App","ImageServer") ?>"){
            $(' <li class="pull-left"> <a href="javascript:;" class="removepic" ><span class="glyphicon glyphicon-remove"></span></a> <img src="<?=Dictionary::indexKeyValue("App","ImageServer")?>'+e.data.thumb+'" /> <input type="hidden" name="Goods[pics][][thumb]" value="'+e.data.thumb+'" /> <input type="hidden" name="Goods[pics][][src]" value="'+e.data.src+'" /> </li>').appendTo("#pics");
            $(document.body).trigger("updatepicsidx");
        }
    });
    $(document.body).on("updatepicsidx",function(){
        var l = $("#pics li").each(function(i,li){
            $("input:hidden",li).each(function(ii,f){
                var $f = $(f);
                $f.attr("name",$f.attr("name").replace(/\[\d*\]/,"["+i+"]"));
            });
        }).length;
        $("#btnaddpic,#form-pic")[l==5?"addClass":"removeClass"]("hide");
    }).trigger("updatepicsidx");
    $("#pics").on("click",".removepic",function(e){
        $(this).parents("li:eq(0)").remove();
        $(document.body).trigger("updatepicsidx");
    }).sortable({placeholderClass:"pull-left splaceholder"}).on("sortupdate",function(){
        $(document.body).trigger("updatepicsidx");
    });
    $("#goods-form").on("beforeSubmit",function(e){
        var error = $("#pics li").length==0,$sgNum = $("#subgoodsattr .number");
        $("#goodspics")[error?"addClass":"removeClass"]("has-error").find(".help-block").html(error?"至少上传一张图片":"");
        if(error){ $("#goodspics")[0].scrollIntoView()  }
        if(vm.featureErr()) error = true;
        $sgNum.parent().removeClass("has-error");
        $sgNum.each(function(i,t){
            if(t.value==""){
                error = true;
                $(t.parentNode).addClass("has-error");
            }
        });
        return !error;
    });
});
</script>
<?php $this->endBlock(); ?>
