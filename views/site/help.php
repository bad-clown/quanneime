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
		<div class="help">
			<div class="help-title">使用帮助</div>
			<div class="question-list">
				<ul>
					<li>
						<h3>无法注册？</h3>
						<p>一、如果手机号码已经注册了的话，是无法再用该号码再次注册，请尝试换个手机号码。</p>
						<p>二、试试换个浏览器，推荐安装 <a href="http://www.google.cn/chrome/browser/desktop/index.html" target="_blank;">chrome</a>  或  <a href="http://se.360.cn/" target="_blank;">360浏览器</a> 。</p>
					</li>
					<li>
						<h3>无法发布？无法投递？</h3>
						<p>一、请检查是否有些 必填项 未输入或选择。</p>
						<p>二、试试换个浏览器，推荐安装 <a href="http://www.google.cn/chrome/browser/desktop/index.html" target="_blank;">chrome</a>  或  <a href="http://se.360.cn/" target="_blank;">360浏览器</a> 。</p>
					</li>
					<li>
						<h3>发布成功但是前台未显示？</h3>
						<p>一、您在成功发布岗位之后，会提交到后台审核，一般很快就会通过并在前台显示。</p>
					</li>
				</ul>
			</div>
			<div class="ewm">
				<div><img src="/images/qnmgzhewm.jpg" width="164" alt=""></div>
				<p>更多使用帮助，请公众号留言，很快就会回复<a href="http://www.quannei.me/">← 返回首页</a></p>
			</div>
		</div>
	</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
$(function() {
	$("#J_Help_").addClass('active');
})
</script>
<?php $this->endBlock();  ?>