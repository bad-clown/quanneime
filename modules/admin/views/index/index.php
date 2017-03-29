<?php
use yii\helpers\Url;
?>
<iframe src="<?= Url::to(['/admin/job',"sort"=>"-time"])?>" frameborder="0" name="mainframe" id="mainframe" ></iframe>
