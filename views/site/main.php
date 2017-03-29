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
	<!-- 内容 start -->
	<div class="main">
		<div class="total-work clearfix">
			<p class="tw-text" style="display: none;"><span>个机会</span></p>
			<i class="tw-icon" style="display: none;"></i>
		</div>
		<div class="main-content clearfix">
			<!-- 已发布工作列表 start -->
			<div class="work-box">
				<ul class="work-lists" id="J_jobList">
					<div class="__loading">
						<img src="<?= $Path;?>/images/loader.gif" alt="">
					</div>
				</ul>
				<div class="work-page">
					<ul class="pages clearfix" id="J_pages">
					</ul>
				</div>
			</div>
			<!-- 已发布工作列表 end -->
			<!-- 筛选类型 start -->
			<div class="screened-content">
				<!-- 公司类型 start -->
				<div class="company-class">
					<div class="class-title">公司类型 <i></i></div>
					<div class="class-list-box" id="J_companyType">
						<div class="class-list">
							<input type="radio" id="radio-1-0" name="radio-1-set" class="regular-radio radio-1-0" checked />
							<label for="radio-1-0" class="label-dot"></label>
							<label for="radio-1-0" class="sc-name j_g_screen" data-type="">全部类型</label>
						</div>
						<?php ksort($comTypeList);foreach($comTypeList as $key => $value) { ?>
						<div class="class-list">
							<input type="radio" id="radio-1-<?=$key?>" name="radio-1-set" class="regular-radio radio-1-<?=$key?>" data-type="<?=$key?>" />
							<label for="radio-1-<?=$key?>" class="label-dot"></label>
							<label for="radio-1-<?=$key?>" class="sc-name j_g_screen"><?=$value?></label>
						</div>
						<?php } ?>
					</div>
				</div>
				<!-- 公司类型 end -->
				<!-- 岗位类型 start -->
				<div class="work-class">
					<div class="class-title">岗位类型 <i></i></div>
					<div class="class-list-box">
						<div class="overflow-box" id="J_positionType">
							<div class="class-list">
								<input type="radio" id="radio-2-0" name="radio-2-set" class="regular-radio radio-1-0" checked />
								<label for="radio-2-0" class="label-dot"></label>
								<label for="radio-2-0" class="sc-name j_g_screen" data-type="">全部类型</label>
							</div>
							<?php ksort($positionTypeList);foreach($positionTypeList as $key => $value) { ?>
							<div class="class-list">
								<input type="radio" id="radio-2-<?=$key?>" name="radio-2-set" class="regular-radio radio-1-<?=$key?>" data-type="<?=$key?>" />
								<label for="radio-2-<?=$key?>" class="label-dot"></label>
								<label for="radio-2-<?=$key?>" class="sc-name j_g_screen"><?=$value?></label>
							</div>
							<?php } ?>
						</div>
						<a href="javascript:void(0)" class="more-position" id="J-more-position">更多</a>
					</div>
				</div>
				<!-- 岗位类型 end -->
			</div>
			<!-- 筛选类型 end -->
		</div>
	</div>
	<!-- 内容 end -->
	<div class="m-fr" id="J_mFr">
		<div class="ewm-code" id="J_cEwm">
			<img src="/images/qrcode.png" width="129" alt="圈内觅" />
			<p>扫码关注及时获取机会</p>
		</div>
		<a class="ewm-icon" id="J_sEwm" href="javascript:void(0)" title="二维码"></a>
		<a class="top-icon" id="to_top" href="javascript:void(0)" title="回到顶部" style="display: none;"></a>
	</div>
<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/pageTotal.js"></script>
<script type="text/x-jquery-tmpl" id="job-list-tmpl">
<li>
	<a href="<?= $Path; ?>/index.php?r=job/detail&id=${_id['$id']}" class="wl-panel" target="_blank">
		<div class="wl-portrait">
			<img src="<?= $Path;?>${publisherAvatar}" height="56" width="56" />
		</div>
		<div class="wl-info">
			<div class="profession-text">
				<span class="profession"><b>${name}</b></span>
			</div>
			<div class="tcompany-box">
				<div class="tcompany"><span>${company} &middot ${city}</span></div>
			</div>
		</div>
		<div class="wl-salary">
			<p><b>￥${salary}${salaryType}</b></p>
			<p class="time-text"><span>${showTime}</span> 发布</p>
		</div>
	</a>
</li>
</script>
<script type="text/javascript">
$(function() {
	$("#J_Home_").addClass('active');
	$(window).on('scroll', function() {
		if($(window).scrollTop() < 100) {
			$('#to_top').hide()
		}
		else {
			$('#to_top').show()
		}
	})
	$('#to_top').click(function(){
		$('body,html').animate({scrollTop:0},200);
	});
	$('.class-title').on('click', function() {
		$this = $(this);
		if($this.hasClass('add-down')){
			$this.next('.class-list-box').slideDown(function() {
				$this.removeClass('add-down');
			});
		}
		else{
			$this.next('.class-list-box').slideUp(function() {
				$this.addClass('add-down');
			});
		}
	})
	$('#J-more-position').on('click', function() {
		$('#J_positionType').removeClass('overflow-box');
		$('#J-more-position').remove()
	})
	var etimer = null;
	$('#J_sEwm').on('click', function(e) {
		e.stopPropagation();
		clearTimeout(etimer)
		if($(this).hasClass('s')) {
			$('#J_cEwm').hide()
			$(this).removeClass('s')
		}
		else {
			$('#J_cEwm').show()
			$(this).addClass('s')
			offsetLeft()
		}
	})
	$('#J_sEwm').on('mouseenter', function(e) {
		e.stopPropagation();
		etimer = setTimeout(function() {
			if(!$('#J_sEwm').hasClass('s')) {
				$('#J_cEwm').show()
				$('#J_sEwm').addClass('s')
				offsetLeft()
			}
		}, 1000)
	})
	$(document).on('click', function() {
		$('#J_cEwm').hide();
		$('#J_sEwm').removeClass('s')
	});

	function offsetLeft() {
		var oLeft = $('#J_cEwm').offset().left;
		var iWidth = $('#J_cEwm').width();
		var outerLeft = oLeft+iWidth;
		var wWidth = $(window).innerWidth();

		if(wWidth < outerLeft) {
			$('#J_cEwm').css({
				'right' : '-50px'
			})
		}
	}


})
</script>
<?php $this->endBlock();  ?>
