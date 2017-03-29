<?php
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
?>
<ul>
	<li id="J_ResumeNav"><a href="<?= $Path;?>/index.php?r=account/personal-resume">我的简历</a></li>
	<li id="J_GatherNav"><a href="<?= $Path;?>/index.php?r=account/personal-gather">我的投递</a></li>
	<li id="J_AccountNav"><a href="<?= $Path;?>/index.php?r=account/personal-account">账号设置</a></li>
	<li id="J_PasswordNav"><a href="<?= $Path;?>/index.php?r=account/personal-password">修改密码</a></li>
	<li id="J_PointsNav"><a href="<?= $Path;?>/index.php?r=account/personal-points">我的积分</a></li>
	<li id="J_RuleNav"><a href="<?= $Path;?>/index.php?r=account/personal-point-rule">积分规则</a></li>
</ul>
