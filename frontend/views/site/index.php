<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = 'Articles';
?>

<h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
<?php //echo $this->render('/article/_search', ['model' => $searchModel]); ?>
<br>
<div class="row">
    <div class="col-md-5">
        <p>Date range</p>
        <?php $form = \yii\widgets\ActiveForm::begin([
            'action' => ['/'],
            'method' => 'post',
            'options' => [
                'data-pjax' => 1,
            ]
        ]); ?>
                <div class="row">
                    <div class="col-md-8">
                        <?php
                            echo \kartik\daterange\DateRangePicker::widget([
                                'model' => $searchModel,
                                'attribute' => 'date_range',
                                'convertFormat' => true,
                                'startAttribute' => 'date_from',
                                'endAttribute' => 'date_to',
                                'pluginOptions' => [
                                    'opens'=>'right',
                                    'locale' => [
                                        'cancelLabel' => 'Clear',
                                        'format' => 'Y-m-d',
                                    ]
                                ],
                            ]);
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?= Html::submitButton('Search', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
        <?php \yii\widgets\ActiveForm::end(); ?>
    </div>
    <div class="col-md-7">
        <div class="pull-right">
            Sort by:
            <?= $dataProvider->sort->link('created_at', ['label' => 'Date']) ?> |
            <?= $dataProvider->sort->link('user', ['label' => 'Author']) ?>
        </div>
    </div>
</div>
<hr>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination' => $dataProvider->pagination,
    'options' => ['class' => 'pagination no-margin pagination-sm pull-right'],
]);
?>
<?php foreach ($dataProvider->getModels() as $article):?>
<div class="row">
    <div class="col-md-12">
        <h2><a data-pjax="0" href="<?= \yii\helpers\Url::to(['article/view', 'id' => $article->id]) ?>"><?=$article->title?></a></h2>
        <p>
            <?=$article->brief?>
            <br>
            <p class="text-right">
                <?=$article->user->username?> | <?= Yii::$app->formatter->asDatetime($article->created_at, 'php:d-m-Y  H:i:s'); ?>
            </p>
        </p>
    </div>
</div>
<?php endforeach;?>


<?php Pjax::end(); ?>
