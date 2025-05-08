<?php

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Construction Sites';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="construction-site-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($canManageConstructionSites): ?>
    <p>
        <?= Html::a('Create Construction Site', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'location',
            [
                'attribute' => 'quadrature',
                'value' => function($model) {
                    return number_format($model->quadrature, 2) . ' m²';
                }
            ],
            'access_level',
            [
                'attribute' => 'created_at',
                'format' => ['datetime']
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $canManageConstructionSites ? '{view} {update} {delete}' : '{view}',
            ],
        ],
    ]); ?>
</div> 