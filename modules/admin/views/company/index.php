<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use app\components\I18n;
use app\modules\admin\models\Company;
use app\modules\admin\models\Dictionary;
use yii\bootstrap\BootstrapPluginAsset;
BootstrapPluginAsset::register($this);
/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\invite-codeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = I18n::text('invite-code');
?>
<div class="company-index">

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            [
                'class' => 'yii\grid\DataColumn',
                'format' => 'html',
                'attribute' => 'logo',
                'label' => $searchModel->getAttributeLabel('logo'),
                'value' => function($model) {
                    return '<img src="' . Dictionary::indexKeyValue('App', 'Host', false) . $model->logo . '" />';
                },
            ],
            'description',
            'keys',
            //['class' => 'app\components\GridActionColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('查看', ['view', 'id' => (string)$model["_id"]], [
                            'class' => 'btn btn-xs btn-info',
                            'data-method' => 'post',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('更新', ['update', 'id' => (string)$model["_id"]], [
                            'class' => 'btn btn-xs btn-info',
                            'data-method' => 'post',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('删除', $url, [
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('user', 'Are you sure to delete this user?'),
                            'title' => Yii::t('yii', 'Delete'),
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>

<?php $this->beginBlock("bottomcode");  ?>
<?php $this->endBlock();  ?>
