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
		<div class="company-gather">
			<ul class="company-gather-list clearfix" id="J_company_gather">
				<?php for($i=0;$i<count($compList);$i++) {?>
				<li <?php if($i < 1){?>class="company-hot"<?php };?>>
					<a href="<?= $Path.'/index.php?r=company/gather&id='.$compList[$i]->_id;?>" target="_blank">
						<img src="<?= $Path.$compList[$i]->logo;?>" height="77" width="202" alt="">
						<strong><?= $compList[$i]->description;?></strong>
						<span><i><?= $compList[$i]->jobCount;?></i> 个岗位</span>
					</a>
				</li>
				<?php }?>
			</ul>
		</div>
	</div>
	<!-- 内容 end -->

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript" src="<?= $Path;?>/js/jquery.1.11.1.min.js"></script>
<script type="text/javascript" src="<?= $Path;?>/js/Extend.js"></script>
<script type="text/javascript">
	$("#J_Company_").addClass('active');
</script>
<?php $this->endBlock();  ?>
