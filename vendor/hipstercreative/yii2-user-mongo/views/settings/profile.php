<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;

YiiAsset::register($this);

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var hipstercreative\user\models\Profile $profile
 */

$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginBlock("bottomcode");  ?>
<style type="text/css">
.tbbh td{word-break:break-all;}
</style>
<?php $this->endBlock();  ?>
<div class="<?=\Yii::getAlias("@m")=="/web"?"p20":""?>">
<?= $this->render('@app/modules/m/views/account/_nav', [ ]) ?>
<div class="row m10tb">
    <?php if (Yii::$app->getSession()->hasFlash('settings_saved')): ?>
        <div class="col-md-12">
            <div class="alert alert-success">
                <?= Yii::$app->getSession()->getFlash('settings_saved') ?>
            </div>
        </div>
    <?php endif; ?>
    <div class="col-md-3">
        <?php if(\Yii::$app->user->identity->openid==null){   ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <a href="<?=Url::to(["@m/security/logout"])?>" class="pull-right" data-method="post">安全退出</a>
                <h3 class="panel-title"><?= Yii::$app->getUser()->getIdentity()->username ?></h3>
            </div>
            <div class="panel-body">
                <?= \yii\widgets\Menu::widget([
                    'options' => [
                        'class' => 'nav nav-pills nav-stacked'
                    ],
                    'items' => [
                        ['label' => Yii::t('user', 'Profile'), 'url' => ['/user/settings/profile']],
                        //['label' => Yii::t('user', 'Email'), 'url' => ['/user/settings/email']],
                        ['label' => Yii::t('user', 'Password'), 'url' => ['/user/settings/password']],
                    ]
                ]) ?>
            </div>
        </div>
        <?php } ?>
    </div>
    <div class="col-md-9">
        <div class="jumbotron p20">
        <h4 class="p20">可用账户余额: <span style="color:#3c763d">￥<?= \Yii::$app->user->identity->balance==null?0:\Yii::$app->user->identity->balance ?></span></h4>
        <h5>账户历史记录</h5>
        <hr/>
        <table class="table tbbh">
            <thead>
                <tr><th class="text-left">时间</th><th>说明</th><th class="text-right" style="width:60px;">金额</th></tr>
            </thead>
            <tbody>
                <?php  
                    foreach ($balanceHistory as $row) {
                ?>
                    <tr> <td class="p0"><?=  \Yii::$app->formatter->asDateTime($row["time"])?></td> <td class=""><?=$row["comment"]?></td> <td  class="p0 text-right">￥<?=$row["balance"]?></td> </tr>
                <?php
                    }
                ?>
            </tbody>
        </table>
        <?php if(count($balanceHistory)==0){   ?>
        <div>无</div>
        <?php }  ?>
        <?=$this->render("@app/modules/web/views/_page",["page"=>$page,"pageCount"=>$pageCount])?>
        </div>
    </div>
</div>
</div>
