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
	<div class="main">
		<div class="ResumeBox">
			<div class="Resume-popup" id="resume-close">
				<div class="Resume-information">
					<div>
						<h3 class="_title">个人信息</h3>
						<ul class="simple-info">
							<li>
								<span class="resume-tit">真实姓名：</span>
								<div class="resume-text"><?= $model->name;?></div>
							</li>
							<li>
								<span class="resume-tit">联系电话：</span>
								<div class="resume-text"><b><?= $model->phoneno;?></b></div>
							</li>
							<li>
								<span class="resume-tit">联系邮箱：</span>
								<div class="resume-text"><?= $model->email;?></div>
							</li>
							<li>
								<span class="resume-tit">出生年份：</span>
								<div class="resume-text"><?= $model->birth;?></div>
							</li>
							<li class="resume-portrait">
								<div class="portrait-img-box"><img src="<?php if($model->avatar == null || $model->avatar == ''){echo $Path.'/images/portrait-bg2.jpg';}else{echo $Path.$model->avatar;};?>" width="227" height="227" alt=""></div>
							</li>
						</ul>
					</div>
					<div>
						<ul class="work-info clearfix">
							<li>
								<span class="resume-tit">目前公司：</span>
								<div class="resume-text"><?= $model->currComp;?></div>
							</li>
							<li class="ml25">
								<span class="resume-tit">当前职位：</span>
								<div class="resume-text"><?= $model->currPosition;?></div>
							</li>
							<li>
								<span class="resume-tit">工作年限：</span>
								<div class="resume-text"><?= $model->workingLife;?></div>
							</li>
							<li class="ml25">
								<span class="resume-tit">所在城市：</span>
								<div class="resume-text"><?= $model->city;?></div>
							</li>
						</ul>
					</div>
					<div>
						<h3 class="_title">个人介绍</h3>
						<div class="introduce">
							<div class="resume-text"><?= $model->intro;?></div>
						</div>
					</div>
					<div>
						<h3 class="_title">教育背景</h3>
						<div class="education-info">
							<?php
							if($model->eduBackground == null || $model->eduBackground == '') {
								$model->eduBackground = [];
							};
							foreach ($model->eduBackground as $key => $value) { ?>
							<ul class="education-panel clearfix">
								<li>
									<span class="resume-tit">就读学校：</span>
									<div class="resume-text"><?= $value['school'];?></div>
								</li>
								<li class="ml25">
									<span class="resume-tit">就读专业：</span>
									<div class="resume-text"><?= $value['major'];?></div>
								</li>
								<li>
									<span class="resume-tit">学历情况：</span>
									<div class="resume-text"><?= Dictionary::indexKeyValue('DegreeType', $value['education']);;?></div>
								</li>
								<li class="ml25">
									<span class="resume-tit">毕业时间：</span>
									<div class="resume-text"><?= $value['graduation'];?></div>
								</li>
							</ul>
							<?php }; ?>
						</div>
					</div>
					<div class="">
						<h3 class="_title">职业意向</h3>
						<ul class="career-intention clearfix">
							<li>
								<span class="resume-tit">意向城市：</span>
								<div class="resume-text"><?= $model->intenCity;?></div>
							</li>
							<li class="ml25">
								<span class="resume-tit">意向岗位：</span>
								<div class="resume-text"><?= $model->intenPosition;?></div>
							</li>
							<li>
								<span class="resume-tit">期望薪资：</span>
								<div class="resume-text"><?php if($model->expectSalary != null || $model->expectSalary != ''){echo $model->expectSalary;if($model->expectSalaryType == 1){echo '元 / 月';}elseif($model->expectSalaryType == 2){echo '万元 / 年';}};?><?php ; ?></div>
							</li>
						</ul>
					</div>
					<div class="documentBox">
						<div class="document clearfix">
							<div class="document-left"><img src="/images/we.png" alt=""></div>
							<div class="document-right">
								<a href="<?php if($model->attachment == ""){ echo 'javascript:void(0);'; }else{ echo $Path.$model->attachment; }; ?>" target="_blank"><?php if($model->attachment == ""){ echo '未上传附件';}else{ echo str_replace("/data/attachment/", "", $model->attachment); }; ?></a>
							</div>
							<div class="document-download">
								<a href="<?php if($model->attachment == ""){ echo 'javascript:void(0);'; }else{ echo $Path.$model->attachment; }; ?>" target="_blank">点击下载</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="resume-overlay"></div>
	</div>


<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$('html,body').css('overflow', 'hidden');
	$('.header').css({'position': 'fixed','top':0,'left':0})
	$('.footer').css({'position': 'fixed','bottom':0,'left':0,'width': '100%'})

	$('#resume-close').on('click', function(e) {
		if(e.target.id == 'resume-close') {
			window.location.href = '<?= $Path;?>/index.php?r=account/comp-resume-list';
		}
	})
})
</script>
<?php $this->endBlock();  ?>
