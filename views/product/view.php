<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\ArrayHelper;
use app\models\Category;

/** @var yii\web\View $this */
/** @var app\models\Product $model */

$this->title = $model->product_name;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php if(Yii::$app->user->identity->user_type == Yii::$app->params['roleAdmin']){?>
    <p>
        <?= Html::a('Update', ['update', 'product_id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'product_id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php } ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category ? $model->category->category_name : ''; // Display category name
                },
                'filter' => \yii\helpers\ArrayHelper::map(app\models\Category::find()->all(), 'category_id', 'category_name'), // Optional: category filter dropdown
            ],
            'product_name',
            'price',
            [
                'attribute' => 'status',
                'value' => $model->status == 1 ? 'Active' : 'Inactive', // Check the status and display corresponding value
            ],
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

</div>
