<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\EmployeeRoles;
use yii\helpers\Url;

$this->title = 'Tasks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($canManageTasks): ?>
    <p>
        <?= Html::a('Create Task', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'task',
            [
                'attribute' => 'employee_id',
                'value' => 'employee.name',
                'label' => 'Employee',
            ],
            [
                'attribute' => 'construction_site_id',
                'value' => 'constructionSite.location',
                'label' => 'Construction Site',
            ],
            'date:date',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => $canManageTasks ? '{view} {update} {delete}' : '{view}',
            ],
        ],
    ]); ?>
</div> 