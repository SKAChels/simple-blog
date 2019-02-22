<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */

$this->title = 'Articles';
?>

<h1><?= Html::encode($this->title) ?></h1>
<?php Pjax::begin(); ?>
<?php //echo $this->render('/article/_search', ['model' => $searchModel]); ?>

<p>
    <?= Html::a('Add Article', ['article/create'], ['class' => 'btn btn-success']) ?>
</p>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [

        'title',
        'brief:ntext',
        [
            'label' => 'Author',
            'attribute' => 'user_id',
            'value' => 'user.username'
        ],
        'created_at:datetime',
        'updated_at:datetime',

        ['class' => 'yii\grid\ActionColumn', 'controller' => 'article'],
    ],
]); ?>
<?php Pjax::end(); ?>
