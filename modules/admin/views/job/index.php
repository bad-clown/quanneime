<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Job;
use app\modules\admin\models\Dictionary;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('Job');
?>
<div class="job-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'company',
            [
                'attribute' => 'salary',
                'value' => function ($model, $index, $widget) {
                    return '' . $model->salary . Dictionary::indexKeyValue('SalaryType', $model->salaryType);
                }
            ],

            'publisherName',
            [
                'attribute' => 'status',
                'value' => function ($model, $index, $widget) {
                    if ($model->status == Dictionary::indexKeyValue('JobStatus', 'Reject')) {
                        return Dictionary::indexKeyValue('JobStatusText', $model->status) . '(原因: ' . $model->rejectMsg . ')';
                    }
                    else {
                        return Dictionary::indexKeyValue('JobStatusText', $model->status);
                    }
                }
            ],
            [
                'attribute' => 'time',
                'value' => function ($model, $index, $widget) {
                    return date('Y-m-d H:i', $model->time);
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{delete} {auth}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a('删除', $url, [
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => '确定删除该职位？',
                            'title' => Yii::t('yii', 'Delete'),
                        ]);
                    },
                    'auth' => function ($url, $model) {
                        if ($model->status == Dictionary::indexKeyValue('JobStatus', 'Pending')) {
                            return Html::a('审核', $url, [
                                'class' => 'btn btn-xs btn-info',
                                'title' => Yii::t('yii', 'View'),
                            ]);
                        }
                        return "";
                    },
                ],
            ],
        ],
    ]); ?>

</div>
