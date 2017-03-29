<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
?>

<div data-role="page" data-quicklinks="true" id="pageMain">
	<div class="ui-panel-wrapper">
		<div data-role="header" data-theme="b">
			<h1>管理中心</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<div class="panel">
				<ul data-role="listview" data-inset="true">
					<li>
						<h3 class="_title">个人信息</h3>
					</li>
					<li>
						<span class="resume-tit">真实姓名：</span>
						<span class="resume-text"><?= $model->name;?></span>
					</li>
					<li>
						<span class="resume-tit">联系电话：</span>
						<span class="resume-text"><b><?= $model->phoneno;?></b></span>
					</li>
					<li>
						<span class="resume-tit">联系邮箱：</span>
						<span class="resume-text"><?= $model->email;?></span>
					</li>
					<li>
						<span class="resume-tit">出生年份：</span>
						<span class="resume-text"><?= $model->birth;?></span>
					</li>
					<li class="resume-portrait">
						<span class="portrait-img-box"><img src="<?php if($model->avatar == null || $model->avatar == ''){echo $Path.'/images/portrait-bg2.jpg';}else{echo $Path.$model->avatar;};?>" width="227" height="227" alt=""></span>
					</li>
					<li>
						<span class="resume-tit">目前公司：</span>
						<span class="resume-text"><?= $model->currComp;?></span>
					</li>
					<li class="ml25">
						<span class="resume-tit">当前职位：</span>
						<span class="resume-text"><?= $model->currPosition;?></span>
					</li>
					<li>
						<span class="resume-tit">工作年限：</span>
						<span class="resume-text"><?= $model->workingLife;?></span>
					</li>
					<li class="ml25">
						<span class="resume-tit">所在城市：</span>
						<span class="resume-text"><?= $model->city;?></span>
					</li>
				</ul>
			</div>
			<div class="panel">
				<ul data-role="listview" data-inset="true">
					<li>
						<h3 class="_title">个人介绍</h3>
					</li>
					<li style="white-space:normal;">
						<div class="resume-text"><?= $model->intro;?></div>
					</li>
				</ul>
			</div>
			<div class="panel">
				<?php
				if($model->eduBackground == null || $model->eduBackground == '') {
					$model->eduBackground = [];
				};
				foreach ($model->eduBackground as $key => $value) { ?>
				<ul data-role="listview" data-inset="true">
					<li>
						<h3 class="_title">教育背景</h3>
					</li>
					<li>
						<span class="resume-tit">就读学校：</span>
						<span class="resume-text"><?= $value['school'];?></span>
					</li>
					<li class="ml25">
						<span class="resume-tit">就读专业：</span>
						<span class="resume-text"><?= $value['major'];?></span>
					</li>
					<li>
						<span class="resume-tit">学历情况：</span>
						<span class="resume-text"><?= Dictionary::indexKeyValue('DegreeType', $value['education']);;?></span>
					</li>
					<li class="ml25">
						<span class="resume-tit">毕业时间：</span>
						<span class="resume-text"><?= $value['graduation'];?></span>
					</li>
				</ul>
				<?php }; ?>
			</div>
			<div class="panel">
				<ul data-role="listview" data-inset="true">
					<li>
						<h3 class="_title">职业意向</h3>
					</li>
					<li>
						<span class="resume-tit">意向城市：</span>
						<span class="resume-text"><?= $model->intenCity;?></span>
					</li>
					<li class="ml25">
						<span class="resume-tit">意向岗位：</span>
						<span class="resume-text"><?= $model->intenPosition;?></span>
					</li>
					<li>
						<span class="resume-tit">期望薪资：</span>
						<span class="resume-text"><?php if($model->expectSalary != null || $model->expectSalary != ''){echo $model->expectSalary;if($model->expectSalaryType == 1){echo '元 / 月';}elseif($model->expectSalaryType == 2){echo '万元 / 年';}};?><?php ; ?></span>
					</li>
				</ul>
			</div>
			<div class="panel">
				<ul data-role="listview" data-inset="true">
					<li>
						<a href="tel:<?= $model->phoneno;?>" style="background-color:#80c269;color: #fff;text-shadow: none;">联系该候选人</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<script>
$(function() {
	$( "body>[data-role='panel']" ).panel();
})
</script>
<?php $this->endBlock();  ?>