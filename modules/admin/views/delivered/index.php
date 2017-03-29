<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\DeliveredResume;
use app\modules\admin\models\Dictionary;
use yii\bootstrap\BootstrapPluginAsset;
BootstrapPluginAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\invite-codeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Delivered Resume');
?>
<div class="delivered-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'header' => '投递者',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->deliverer != null) {
                        return $model->deliverer->username;
                    }
                    else {
                        return '';
                    }
                },
            ],
            [
                'header' => '投递公司',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->job != null) {
                        return $model->job->company;
                    }
                    else {
                        return '';
                    }
                },
            ],
            [
                'header' => '投递职位',
                'format' => 'raw',
                'value' => function($model) {
                    if ($model->job != null) {
                        return $model->job->name;
                    }
                    else {
                        return '';
                    }
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'format' => 'html',
                'attribute' => 'time',
                'label' => $searchModel->getAttributeLabel('time'),
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->time);
                },
            ],
            [
                'class' => 'yii\grid\DataColumn',
                'format' => 'html',
                'attribute' => 'status',
                'label' => $searchModel->getAttributeLabel('status'),
                'value' => function($model) {
                    if ($model->status == Dictionary::indexKeyValue('DeleveredResumeStatus', 'Delivered')) {
                        return '未查看';
                    }
                    else if ($model->status == Dictionary::indexKeyValue('DeleveredResumeStatus', 'Viewed')) {
                        return '已查看';
                    }
                    else {
                        return '已拒绝';
                    }

                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('查看简历', ['/account/comp-view-resume', 'delivererId' => (string)$model->delivererId, 'jobId' => (string)$model->jobId], [
                            'class' => 'btn btn-xs btn-info',
                            //'data-method' => 'post',
                            'target' => 'blank',
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>

<?php $this->beginBlock("bottomcode");  ?>
<?php $this->endBlock();  ?>
