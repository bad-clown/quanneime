<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\components\I18n;
use app\modules\admin\models\Dictionary;
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
?>
	<div class="main">
		<div class="contact">
			<div class="about">
				<h3>关于我们</h3>
				<p>圈内觅 Quannei.me 是一个专注于地产圈职业机会的平台</p>
			</div>
			<div class="us">
				<h3>联系我们</h3>
				<p>合作、友情链接、问题反馈发送至</p>
				<p style="font-size: 24px;">quannei[at]quannei.me ( [at]替换成@ )</p>
			</div>
			<div class="line-or"><span>或</span></div>
			<div class="ewm">
				<div><img src="/images/qnmgzhewm.jpg" width="164" alt=""></div>
				<p>微信扫码，关注公众号联系到我们</p>
			</div>
		</div>
	</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<?php $this->endBlock();  ?>