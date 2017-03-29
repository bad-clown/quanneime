<?php
use app\modules\admin\logic\DictionaryLogic;
//$Path = DictionaryLogic::indexKeyValue('App', 'Host', false);
$Path = \Yii::$app->request->hostInfo;
/*if(!ereg('/https/', $Path)) {
	$Path = preg_replace('/http/', 'https', $Path);
}*/
?>
<ul>
	<li id="J_PublishNav"><a href="<?= $Path;?>/index.php?r=account/comp-publish">我的发布</a></li>
	<li id="J_ListNav"><a href="<?= $Path;?>/index.php?r=account/comp-resume-list">已收简历</a></li>
	<li id="J_AccountNav"><a href="<?= $Path;?>/index.php?r=account/comp-account">账号设置</a></li>
	<li id="J_Notify"><a href="<?= $Path;?>/index.php?r=account/comp-set-notify">提醒设置</a></li>
	<li id="J_AuthNav"><a href="<?= $Path;?>/index.php?r=account/comp-auth">账号认证</a></li>
	<li id="J_PasswordNav"><a href="<?= $Path;?>/index.php?r=account/comp-password">修改密码</a></li>
	<li id="J_PointsNav"><a href="<?= $Path;?>/index.php?r=account/comp-points">我的积分</a></li>
	<li id="J_RuleNav"><a href="<?= $Path;?>/index.php?r=account/comp-point-rule">积分规则</a></li>
</ul>
