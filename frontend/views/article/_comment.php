<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Comment */
/* @var $article common\models\Article */
/* @var $success_message boolean */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin([
    'action' => ['article/create-comment'],
    'method' => 'post',
    'options' => [
        'data-pjax' => 1,
    ]
]); ?>

<?php if ($success_message):?>
    <div class="alert alert-success fade in alert-dismissible" style="margin-top: 18px;">
        <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
        Comment was added
    </div>
<?php endif;?>

<?= $form->field($model, 'article_id')->hiddenInput(['value' => $article->id])->label(false) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="col-md-8">
        <?= $form->field($model, 'content')->textarea(['rows' => 4]) ?>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Comment', ['class' => 'btn btn-success pull-right']) ?>
</div>

<?php ActiveForm::end(); ?>