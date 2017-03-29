<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\InviteCode;
use app\modules\admin\models\Dictionary;
use yii\bootstrap\BootstrapPluginAsset;
BootstrapPluginAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\invite-codeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('invite-code');
?>
<div class="invite-code-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'code',
            'userId',
            [
                'class' => 'yii\grid\DataColumn',
                'attribute' => 'status',
                'label' => $searchModel->getAttributeLabel('status'),
                'value' => function($model) {
                    return Dictionary::indexKeyValue('InviteCodeStatus', $model['status']);
                },
            ],
        ],
    ]); ?>

</div>
<!-- Modal -->
<div class="modal fade" id="dlgcreate" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= I18n::text("Create")?></h4>
      </div>
      <div class="modal-body">
            <form class="form-inline">
                <div class="form-group" id="cgroup">
                    <label >数量</label>
                    <input type="text" id="count" name="count" value="" class="form-control" />
                </div>
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?= I18n::text("Cancel") ?></button>
        <button type="button" class="btn btn-primary" id="btngen">生成</button>
      </div>
    </div>
  </div>
</div>

<?php $this->beginBlock("bottomcode");  ?>
<script type="text/javascript">
$(function(){
    $("#btngen").click(function(){
        var c = $("#count").val();
        $("#cgroup")[(isNaN(c) || c=="")?"addClass":"removeClass"]("has-error");
        if(!$("#cgroup").is(".has-error")){
            $.getJSON("<?= Url::to(["generate","count"=>""]) ?>"+c).then(function(){
                window.location.reload();
            });
        }
    });
});
</script>
<?php $this->endBlock();  ?>
