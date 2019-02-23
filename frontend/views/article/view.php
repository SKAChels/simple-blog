<?php

use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Article */
/* @var $comments array */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Articles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="article-view">

    <h1>
        <?= Html::encode($this->title) ?>
        <?php if ($model->status === \common\models\Article::STATUS_DRAFT):?>
            <span style="color:#710909;font-size: 14px;"> draft</span>
        <?endif;?>
    </h1>

    <?php if (Yii::$app->user->can('updateArticle', ['article' => $model])):?>
        <p>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif;?>

    <?= $model->content; ?>

    <br><br>
    <p>Author: <?= $model->user->username; ?>
    <p>Date: <?= Yii::$app->formatter->asDatetime($model->created_at, 'php:d-m-Y  H:i:s'); ?></p>
    <br>
    <hr>
    <div class="clearfix"></div>
    <?php Pjax::begin(); ?>
        <?php if (count($comments)):?>
            <div class="row">
                <div class="col-md-6"><h3>Comments</h3></div>
                <div class="col-md-6">
                    <?php
                    echo \yii\widgets\LinkPager::widget([
                        'pagination' => $pagination,
                        'options' => ['class' => 'pagination no-margin pagination-sm pull-right'],
                    ]);
                    ?>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php foreach ($comments as $comment):?>
                <div class="panel panel-info">
                    <div class="panel-heading"><?=$comment->author?>  <?=Yii::$app->formatter->asDatetime($comment->created_at, 'php:d-m-Y  H:i:s');?></div>
                    <div class="panel-body"><?=$comment->content?></div>
                </div>
            <?php endforeach;?>
            <hr>
        <?php endif;?>
    <?php Pjax::end(); ?>
    <?php Pjax::begin(['enablePushState' => false]); ?>
        <h3>Comment form</h3>
        <?= $this->render('_comment', [
            'model' => $comment_model,
            'article' => $model,
            'success_message' => false,
        ]);
        ?>
        <div class="clearfix"></div>
    <?php Pjax::end(); ?>
</div>
