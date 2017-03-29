<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\admin\models\Dictionary;
use yii\bootstrap\BootstrapPluginAsset;

if($this->context->module->id=='user'){
    BootstrapPluginAsset::register($this);
}

$language = \Yii::$app->language;

?><?php $this->beginPage() ?><!DOCTYPE html>
<!--[if lt IE 7]><html class="lt-ie9 lt-ie8 lt-ie7"  lang="<?= $language    ?>"><![endif]-->
<!--[if IE 7]><html class="lt-ie9 lt-ie8"  lang="<?= $language   ?>"><![endif]-->
<!--[if IE 8]><html class="lt-ie9"  lang="<?= $language   ?>"><![endif]-->
<!--[if gt IE 8]><!--><html  lang="<?= $language   ?>"><!--<![endif]-->
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
    <!--[if lt IE 9]><!-->
<script type="text/javascript" src="<?= Url::to(["@web/static/js/oldbrowsers.js"])?>"></script>
    <!--<![endif]-->

    <?php $this->head() ?>
    <!-- build:css css/global.css -->
    <link rel="stylesheet" type="text/css" href="<?=Url::to(["@web/static/bootstrap/css/bootstrap.min.css"])?>" />
    <link rel="stylesheet" type="text/css" href="<?=Url::to(["@web/static/css/site.css"])?>" />
    <!-- endbuild -->
</head>
<body  data-baseurl="<?=Url::to(["/"])?>">

<?php $this->beginBody() ?>

<?php if (isset($this->blocks['topcode'])): ?>
<?= $this->blocks['topcode'] ?>
<?php  endif; ?>

<div class="web-header-outter" id="siteheader">
        <div class="web-container web-header-inner clearfix">
            <a href="<?=Url::to(["@m/index"])?>" style="margin-bottom:-10px;margin-right:20px;margin-top:-1px;font-size: 30px;line-height: 52px;padding: 0 20px;color:#337AB7;"  class="pull-left" title="iBangBug">
                iBangBuy
            </a>

            <div class="pull-left">
           <a href="<?= Url::to(["@m/index"]) ?>" class="<?= (\Yii::$app->requestedRoute==""  || \Yii::$app->requestedRoute=="web" || \Yii::$app->requestedRoute=="web/index" || \Yii::$app->requestedRoute=="web/index/index")?"active":""  ?>">首页</a>
                <a href="<?= Url::to(["@m/index/list"]) ?>" class="<?= stripos( \Yii::$app->requestedRoute,"list")===FALSE?"":" active " ?>">浏览全部</a>
            </div>

            <ul class="nav navbar-nav navbar-right nav-pills">
                <?php
                    if(\Yii::$app->user->isGuest){
                ?>
                    <li ><a href="<?=Url::to(["@m/security/login"])?>">登录</a></li>
                    <li ><a href="<?=Url::to(["@m/registration/register"])?>">注册</a></li>
                <?php }else{?>
                    <li ><a href="<?=Url::to(["@m/account/order"])?>">我的订单</a></li>
                    <li class="dropdown" >
                        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:;"><?=\Yii::$app->user->identity->username?><span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li> <a href="<?=Url::to(["@m/account/address"])?>">收货地址</a> </li>
                            <li> <a href="<?=Url::to(["@m/settings/profile"])?>">我的账号</a> </li>
                            <!--<li> <a href="<?=Url::to(["@m/account/cashback"])?>">我的返现</a> </li>-->
                            <li role="separator" class="divider"></li>
                            <?php if ((! \Yii::$app->user->isGuest ) &&  (in_array(\Yii::$app->user->identity->username, Dictionary::indexMap("AdminUsers"))) ){?>
                                <li> <a href="<?=Url::to(["/admin/"])?>">网站管理</a> </li>
                                <li role="separator" class="divider"></li>
                            <?php }     ?>
                            <li> <a href="<?=Url::to(["@m/security/logout"])?>" data-method="post">安全退出</a> </li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>
            <a href="<?=Url::to(["@m/cart/items"])?>" id="cartAmount" class="web-cart-tip pull-right"  
                data-html="true" data-toggle="popover" data-trigger=" focus" 
                title="我的购物车" 
                data-content="<div id='cartitems' class='cartitems'><p class='text-center'>加载中...</p><p><a href='<?=Url::to(["@m/cart/items"])?>' class='btn btn-danger block'>提交订单</a></p></div>" 
                data-placement="bottom">无</a>

        </div>
    </div>
    <div class="web-nav-outter">
        <div class="web-container">
            <div class="web-nav">
                <form class="pull-left" action="<?=Url::to(["@m/index/list"])?>">
                    <input type="hidden" name="r" value="web/index/list"/>
                    <input type="text" name="key" class="txtkey" placeholder="搜索商品，比如：iPhone" />&nbsp;&nbsp;
                    <button type="submit" class="btnsearch"><span class="glyphicon glyphicon-search" aria-hidden="true"></span>&nbsp;搜索</button>

                </form>
                <!-- <div class="pull-left">
                    <img src="//img.iBangBug.im/images/slogan.png" height="40px"/>
                </div> -->
            </div>
        </div>
    </div>


<div class="web-container clearfix">

<?= $content ?>

</div>


<?php $this->endBody() ?>
<?php
if($this->context->module->id!='user' && $this->context->module->id!="admin"){
?>
    <!-- build:js js/global.js -->
    <script  type="text/javascript" src="<?=Url::to(["@web/static/js/jquery.js"])?>"></script>
    <script  type="text/javascript" src="<?=Url::to(["@web/assets/fca81d21/yii.js"])?>"></script>
    <script  type="text/javascript" src="<?=Url::to(["@web/static/bootstrap/js/bootstrap.min.js"])?>"></script>
    <script  type="text/javascript" src="<?=Url::to(["@web/static/js/easyzoom.js"])?>"></script>
    <script  type="text/javascript" src="<?=Url::to(["@web/static/js/tmpl.min.js"])?>"></script>
    <script  type="text/javascript" src="<?=Url::to(["@web/static/js/site.js"])?>"></script>
    <!-- endbuild -->
<?php }else{ ?>
    <script  type="text/javascript" src="<?=Url::to(["@web/static/js/site.js"])?>"></script>
<?php } ?>
<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>
</body>
</html>
<?php $this->endPage() ?>

