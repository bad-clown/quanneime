<?php 
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use app\assets\AppAsset;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
if(!isset($this->context->unRegisterAppAsset)){
    AppAsset::register($this);//注册前端资源
}
if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}
//用户登录了。取用户设置的语言优先
$language =\Yii::$app->language;
//是否是内容页，默认都是，内容页不需要菜单
$isContentPage = !isset($this->context->notContentPage);
?><?php $this->beginPage() ?><!DOCTYPE html>
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <link rel="shortcut icon" type="image/ico" href="/favicon.ico" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Dictionary::indexKeyValue("App","SystemName") ?><?= (isset($this->context->title) && (!empty($this->context->title)))?"-".$this->context->title:"" ?></title>
	<?php
    /*$this->registerJs('var $_Path="'.$Path.'"', 1);
	$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/common/common.css',]);
	$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/page/main.css',]);
	$this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/page/usercenter.css',]);
    $this->registerLinkTag(['rel' => 'stylesheet','type' => 'text/css','href' => $Path.'/css/page/popup.css',]);*/
	?>
    <?php $this->head() ?>
    <link rel="stylesheet" type="text/css" href="<?=$Path . '/css/admin/admin.css'; ?>" />
</head>
<body <?= isset($_GET["fullscreen"])?' class="fullscreen" ':"" ?> >

<?php $this->beginBody() ?>
<?php if (isset($this->blocks['topcode'])): ?>
<?= $this->blocks['topcode'] ?>
<?php  endif; ?>
<?php
    if($isContentPage){
?>
<?= $content ?>
<?php
    }else{ // is not contentpage
?>
<?php
    NavBar::begin([
        'brandLabel' => Dictionary::indexKeyValue("App","SystemName")."&nbsp;<sub>回网站首页</sub>",
        'brandUrl' => \Yii::$app->homeUrl,
        //'brandUrl' => "javascript:;",
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $items=[];
    $items[] = ['label' =>  "职位审核", 'url' => Url::to(['/admin/job',"sort"=>"-time"]),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "投递列表", 'url' => Url::to(['/admin/delivered','sort'=>'-time']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "私人会面", 'url' => Url::to(['/admin/meeting']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "用户管理", 'url' => Url::to(['/user/admin']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "订阅邮件", 'url' => Url::to(['/admin/subscribe-mail']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "订阅者管理", 'url' => Url::to(['/admin/subscriber']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "公司管理", 'url' => Url::to(['/admin/company']),'linkOptions'=>["target"=>"mainframe"]];
    $items[] = ['label' =>  "系统定制", 'url' => Url::to(['/admin/dictionary']),'linkOptions'=>["target"=>"mainframe"]];
    if((!\Yii::$app->user->isGuest) ){
        $items[]=  ['label' => '安全退出 (' . Yii::$app->user->identity->username . ')', 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $items,
        ]);
    NavBar::end();
?>

<div class='wrap' >
<?= $content ?>
</div>

<?php
    }// end if is not contentpage
?>
<?php $this->endBody() ?>

<?php if (isset($this->blocks['bottomcode'])): ?>
<?= $this->blocks['bottomcode'] ?>
<?php  endif; ?>

</body>
</html>
<?php $this->endPage() ?>
