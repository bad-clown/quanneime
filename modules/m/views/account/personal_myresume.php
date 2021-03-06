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
			<h1>我的简历</h1>
			<a href="#left-panel" class="ui-btn-left ui-btn-corner-all ui-btn ui-icon-bars ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">导航</a>
			<a href="#pageSearch" class="ui-btn-right ui-btn-corner-all ui-btn ui-icon-search ui-btn-icon-notext ui-shadow" data-form="ui-icon" data-role="button" role="button">搜索</a>
		</div><!-- /header -->
		<div data-role="content">
			<div class="Usercenter-content clearfix">
				<div class="Usercenter-resume Usercenter-right">
					<div class="ResumeNav clearfix">
						<div class="NavBtn NavCur"><a href="javascript:;">在线简历</a></div><!-- 
						<div class="NavBtn"><a href="javascript:;">上传附件</a></div> -->
					</div>
					<div class="ResumeContent">
						<!-- 在线简历 -->
						<div class="ResumePanel ResumeInfo">
							<?php $form = ActiveForm::begin([
								'options'=>['data-ajax' => 'false']
							]); ?>
							<div>
								<h3 class="_title">个人信息</h3>
								<ul class="simple-info">
									<li>
										<?= $form->field($model, 'name', ['inputOptions' => ['class' => 'resume-text'], 'template' => "<label class='control-label resume-tit'>真实姓名</label>\n{input}\n{hint}\n{error}"]) ?>
									</li>
									<li>
										<?= $form->field($model, 'phoneno', ['inputOptions' => ['class' => 'resume-text'], 'template' => "<label class='control-label resume-tit'>您的手机</label>\n{input}\n{hint}\n{error}"]) ?>
									</li>
									<li>
										<?= $form->field($model, 'email', ['inputOptions' => ['class' => 'resume-text'], 'template' => "<label class='control-label resume-tit'>联系邮箱</label>\n{input}\n{hint}\n{error}"]) ?>
									</li>
									<li>
										<?php $allYear=[];$nowYear = date("Y");$minYear = $nowYear-50;for($i=$minYear;$i<=$nowYear;$i++){$allYear[$i] = $i;}?>
										<?= $form->field($model, 'birth', ['inputOptions' => ['class' => 'resume-select']])->dropDownList($allYear, ['prompt'=>"请选择"]) ?>
									</li>
									<li>
										<?= $form->field($model, 'avatar', ['template' => "{input}\n{hint}\n{error}"])->hiddenInput() ?>
									</li>
									<li class="resume-portrait">
										<div class="portrait-img-box"><img src="<?php if($model->avatar == ""){ echo $Path.'/images/portrait-bg2.jpg'; }else{ echo $Path.$model->avatar; }; ?>" width="227" height="227" id="J_portrait" alt="头像"></div>
										<div class="_overlay"></div>
										<div class="resume-portrait-openimg">
											<!-- <input type="button" name="" id="J_portrait_filebtn" class="resume-portrait-filebtn" value="上传简历头像"> -->
											<input name="pic" type="file" id="J_portrait_file" class="resume-portrait-file">
										</div>
										<div class="upload-tips">请上传正方形的图片文件，大小不超过1M</div>
									</li>
								</ul>
							</div>
							<div class="panel-info">
								<div>
									<ul class="work-info clearfix">
										<li>
											<?= $form->field($model, 'currComp', ['inputOptions' => ['class' => 'resume-text', 'placeholder' => '已工作填单位 学生填学校']]) ?>
										</li>
										<li class="ml25">
											<?= $form->field($model, 'currPosition', ['inputOptions' => ['class' => 'resume-text']]) ?>
										</li>
										<li>
											<?= $form->field($model, 'workingLife', ['inputOptions' => ['class' => 'resume-text']]) ?>
										</li>
										<li class="ml25">
											<?= $form->field($model, 'city', ['inputOptions' => ['class' => 'resume-text'], 'template' => "<label class='control-label' for='resume-city'>所在城市</label>\n{input}\n{hint}\n{error}"]) ?>
										</li>
									</ul>
								</div>
								<div>
									<h3 class="_title">个人介绍</h3>
									<div class="introduce">
										<?= $form->field($model, 'intro', ['inputOptions' => ['placeholder' => '输入您的个人介绍', 'class' => 'introduce-textarea'], 'template' => "{input}\n{hint}\n{error}"])->textarea() ?>
									</div>
								</div>
								<div>
									<h3 class="_title">教育背景</h3>
									<div class="education-info" id="J_education">
										<?php if($model->eduBackground == null || $model->eduBackground == '') { ?>
										<ul class="education-panel clearfix">
											<li>
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的学校</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][0][school]">
													<div class="help-block"></div>
												</div>
											</li>
											<li class="ml25">
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的专业</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][0][major]">
													<div class="help-block"></div>
												</div>
											</li>
											<li>
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的学历</label>
													<select name="Resume[eduBackground][0][education]" class="resume-select">
														<option value="0">请选择</option>
														<?php $option = DictionaryLogic::indexMap('DegreeType');
														foreach($option as $k => $v) { ?>
														<option value="<?= $k?>" ><?= $v?></option>
														<?php } ?>
													</select>
													<div class="help-block"></div>
												</div>
											</li>
											<li class="ml25">
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">毕业时间</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][0][graduation]">
													<div class="help-block"></div>
												</div>
											</li>
											<a href="javascript:;" class="add-education J_add_edu"></a>
										</ul>
										<?php } else {
											foreach ($model->eduBackground as $key => $value) {
										?>
										<ul class="education-panel clearfix">
											<li>
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的学校</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][<?= $key?>][school]" value="<?= $value['school'];?>">
													<div class="help-block"></div>
												</div>
											</li>
											<li class="ml25">
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的专业</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][<?= $key?>][major]" value="<?= $value['major'];?>">
													<div class="help-block"></div>
												</div>
											</li>
											<li>
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">您的学历</label>
													<select name="Resume[eduBackground][<?= $key?>][education]" class="resume-select" value="<?= $value['education'];?>">
														<option value="0">请选择</option>
														<?php $option = DictionaryLogic::indexMap('DegreeType'); 
														foreach($option as $k => $v) { ?>
														<?php if($k == $value['education']){ ?>
														<option value="<?= $k?>" selected><?= $v?></option>
														<?php }else { ?>
														<option value="<?= $k?>" ><?= $v?></option>
														<?php }} ?>
													</select>
													<div class="help-block"></div>
												</div>
											</li>
											<li class="ml25">
												<div class="form-group field-resume-eduBackground">
													<label class="control-label">毕业时间</label>
													<input type="text" class="resume-text" name="Resume[eduBackground][<?= $key?>][graduation]" value="<?= $value['graduation'];?>">
													<div class="help-block"></div>
												</div>
											</li>
											<a href="javascript:;" class="add-education <?php if(count($model->eduBackground)-1 == $key){echo 'J_add_edu'; }else{echo 'delete-icon J_delete_edu';}; ?>"></a>
										</ul>
										<?php }};?>
									</div>
									<script type="text/javascript">var eduCount=<?= count($model->eduBackground);?>;</script>
								</div>
								<div>
									<h3 class="_title">职业意向</h3>
									<ul class="career-intention clearfix">
										<li>
											<?= $form->field($model, 'intenCity', ['inputOptions' => ['class' => 'resume-text']]) ?>
										</li>
										<li class="ml25">
											<?= $form->field($model, 'intenPosition', ['inputOptions' => ['class' => 'resume-text']]) ?>
										</li>
										<li>
											<div class="resume-salary">
												<?= $form->field($model, 'expectSalaryType', ['inputOptions' => ['value' => '1', 'data-stype' => '2'], 'template' => "{input}\n{hint}\n{error}"])->hiddenInput() ?>

												<?= $form->field($model, 'expectSalary', ['inputOptions' => ['placeholder' => '输入月薪', 'data-state' => '0', 'data-placeholder' => '输入年薪', 'class' => 'resume-text']]) ?>
												<?php if($model->expectSalaryType == 1){ ?>
												<span class="j_g_salary" data-u="万元 / 年">元 / 月</span>
												<?php }else{ ?>
												<span class="j_g_salary" data-u="元 / 月">万元 / 年</span>
												<?php } ?>
												<span class="salary-msg">可以填写区间如“2000-8000”<br>点击可以切换月薪和年薪</span>
											</div>
										</li>
									</ul>
								</div>
							</div>
							<div class="panel-info" style="display: none;">
								<h3 class="_title">上传附件</h3>
								<div class="upload-document clearfix">
									<div class="upload-document-tip"><a href="<?php if($model->attachment == ""){ echo 'javascript:void(0);'; }else{ echo $Path.$model->attachment; }; ?>" target="_blank" id="J_upload_doc"><?php if($model->attachment == ""){ echo '还未上传附件';}else{ echo str_replace("/data/attachment/", "", $model->attachment); }; ?></a></div>
									<div class="upload-document-openbtn">
										<!-- <input type="button" name="" id="J_upload_attachmentbtn" class="upload-document-filebtn" value="上传附件"> -->
										<input name="file" type="file" id="J_upload_attachment" class="upload-document-file">
									</div>
								</div>
								<div class="document-tips">请选择5M以内的doc、docx、zip、rar、pdf文件</div>
								<?= $form->field($model, 'attachment', ['template' => "{input}\n{hint}\n{error}"])->hiddenInput() ?>
							</div>
							<div class="resume-submit">
								<?= Html::submitButton(Yii::t('user', '保存简历'), ['class' => 'submit-btn']) ?>
							</div>
							<?php ActiveForm::end(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /page -->

<?php $this->beginBlock("bottomcode"); ?>
<script type="text/javascript" src="<?= $Path;?>/js/mobileinit.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.mobile/jquery.mobile-1.4.5.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/layer.js"></script>
<?php if($success) {?>
<script>
$(function() {
	layer.msg('保存成功！', {
		time: 2000
	});
})
</script>
<?php } ?>
<script>
$(function() {
	$( "body>[data-role='panel']" ).panel();

	$('.NavBtn').on('click', function() {
		$(this).addClass('NavCur').siblings().removeClass('NavCur');
		$('.panel-info').eq($(this).index()).show().siblings('.panel-info').hide();
	})

	$('.j_g_salary').on('click', function() {
		var _ 		= 	$(this);
		var _o		=	$('#resume-expectsalary');
		var _s		=	$('#resume-expectsalarytype');
		var _u1 	= 	_.data('u');
		var _u2 	= 	_.html();
		var _p1 	= 	_o.attr('placeholder');
		var _p2 	= 	_o.data('placeholder');
		var _s1		=	_s.val();
		var _s2		=	_s.data('stype');

		_s.val(_s2);
		_s.data('stype', _s1);
		_o.attr('placeholder', _p2);
		_o.data('placeholder', _p1);
		_.data('u', _u2);
		_.html(_u1);
	})

	$('#J_portrait_file').on('change', function() {
		var imgPath = $("#J_portrait_file").val();
		if (imgPath == "") {
			alert("请选择上传图片！");
			return;
		}

		var pic = new FormData()
		$.each($("#J_portrait_file")[0].files, function(i, file) {
			pic.append('pic', file)
		})

		//判断上传文件的后缀名
		var strExtension = imgPath.substr(imgPath.lastIndexOf('.') + 1);
		strExtension = strExtension.toLowerCase();
		if (strExtension != 'jpg' && strExtension != 'gif' && strExtension != 'png' && strExtension != 'bmp') {
			alert("请选择图片文件");
			return;
		}

		$.ajax({
			type: "POST",
			url: apiUrl._updateResumeImg,
			data: pic,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				$('#J_portrait').attr('src', $_Path+data.url)
				$('#resume-avatar').val(data.url)
				alert("上传成功");
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("上传失败，请检查网络后重试");
			}
		});
	})

	$('#J_upload_attachment').on('change', function() {
		var imgPath = $("#J_upload_attachment").val();
		if (imgPath == "") {
			alert("请选择上传文件！");
			return;
		}

		var data2 = new FormData()
		$.each($("#J_upload_attachment")[0].files, function(i, file) {
			data2.append('file', file)
		})

		//判断上传文件的后缀名
		var strExtension = imgPath.substr(imgPath.lastIndexOf('.') + 1);
		strExtension = strExtension.toLowerCase();
		if (strExtension != 'doc' && strExtension != 'docx' && strExtension != 'zip' && strExtension != 'rar' && strExtension != 'pdf') {
			alert("请选择doc、docx、zip、rar、pdf文件");
			return;
		}

		$.ajax({
			type: "POST",
			url: apiUrl._updateResumeAttachment,
			data: data2,
			cache: false,
			contentType: false,
			processData: false,
			dataType: 'json',
			success: function(data) {
				$('#J_upload_doc').attr('href', $_Path+data.url).html(data.url.replace('\/data\/attachment\/', ''))
				$('#resume-attachment').val(data.url)
				alert("上传成功!");
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				alert("上传失败，请检查网络后重试!");
			}
		});
	})

	$(document).on('click', '.J_add_edu', function() {
		eduCount++;
		$(this).removeClass('J_add_edu').addClass('delete-icon J_delete_edu')
		$('#J_education').append('<ul class="education-panel clearfix">'+
									'<li>'+
										'<div class="form-group field-resume-eduBackground">'+
											'<label class="control-label">您的学校</label>'+
											'<input type="text" class="resume-text" name="Resume[eduBackground]['+eduCount+'][school]">'+
											'<div class="help-block"></div>'+
										'</div>'+
									'</li>'+
									'<li class="ml25">'+
										'<div class="form-group field-resume-eduBackground">'+
											'<label class="control-label">您的专业</label>'+
											'<input type="text" class="resume-text" name="Resume[eduBackground]['+eduCount+'][major]">'+
											'<div class="help-block"></div>'+
										'</div>'+
									'</li>'+
									'<li>'+
										'<div class="form-group field-resume-eduBackground">'+
											'<label class="control-label">您的学历</label>'+
											'<select name="Resume[eduBackground]['+eduCount+'][education]" class="resume-select">'+
												'<option value="0">请选择</option>'+
												<?php $option = DictionaryLogic::indexMap('DegreeType');
												foreach($option as $k => $v) { ?>
												'<option value="<?= $k?>"><?= $v?></option>'+
												<?php } ?>
											'</select>'+
											'<div class="help-block"></div>'+
										'</div>'+
									'</li>'+
									'<li class="ml25">'+
										'<div class="form-group field-resume-eduBackground">'+
											'<label class="control-label">毕业时间</label>'+
											'<input type="text" class="resume-text" name="Resume[eduBackground]['+eduCount+'][graduation]">'+
											'<div class="help-block"></div>'+
										'</div>'+
									'</li>'+
									'<a href="javascript:;" class="add-education J_add_edu"></a>'+
								'</ul>')
	})

	$(document).on('click', '.J_delete_edu', function() {
		var parentUl = $(this).parents('.education-panel')
		parentUl.remove()
	})

	$('#resume-form').on('submit', function(e) {
		var o = $('.field-resume-eduBackground').find('.resume-text');
		var o2 = $('.field-resume-eduBackground').find('.resume-select');

		o2.each(function(i, o) {
			if($(o).val() == null || $(o).val() == 0) {
				e.preventDefault();
				$(o).focus();
				$(o).next('.help-block').html($(o).prev('.control-label').html()+'不能为空。');
			}
			else{
				$(o).next('.help-block').html("")
			}
		})
		o.each(function(i, o) {
			if($(o).val() == null || $(o).val() == "") {
				e.preventDefault();
				$(o).focus();
				$(o).next('.help-block').html($(o).prev('.control-label').html()+'不能为空。');
			}
			else {
				$(o).next('.help-block').html("")
			}
		})
	})
	$(document).on('blur', '#J_education .resume-text', function(e) {
		if($(this).val() == null || $(this).val() == "") {
			e.preventDefault();
			//$(o).focus();
			$(this).next('.help-block').html($(this).prev('.control-label').html()+'不能为空。');
		}
		else {
			$(this).next('.help-block').html("")
		}
	});
	$(document).on('blur', '#J_education .resume-select', function(e) {
		if($(this).val() == null || $(this).val() == "") {
			e.preventDefault();
			//$(o).focus();
			$(this).next('.help-block').html($(this).prev('.control-label').html()+'不能为空。');
		}
		else {
			$(this).next('.help-block').html("")
		}
	});

})
</script>
<?php $this->endBlock();  ?>