<?php

/** @var yii\web\View $this */
/** @var app\models\ConstructionSite $model */
/** @var string $managers */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = $model->location;
$this->params['breadcrumbs'][] = ['label' => 'Construction Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="construction-site-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if ($canManageConstructionSites): ?>
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif; ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'location',
            [
                'attribute' => 'quadrature',
                'value' => function($model) {
                    return number_format($model->quadrature, 2) . ' m²';
                }
            ],
            'access_level',
            [
                'label' => 'Managers',
                'format' => 'raw',
                'value' => $managers,
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

    <h2><?= $isEmployee ? 'Your Tasks' : 'Tasks' ?> at this Construction Site</h2>

    <?= GridView::widget([
        'dataProvider' => $tasksDataProvider,
        'columns' => [
            'task',
            [
                'attribute' => 'employee_id',
                'value' => 'employee.name',
                'label' => 'Employee',
                'visible' => !$isEmployee,
            ],
            'date:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('Open task',
                            ['/task/view', 'id' => $model->id], 
                            ['title' => 'View']
                        );
                    },
                ],
            ],
        ],
    ]); ?>
</div> 