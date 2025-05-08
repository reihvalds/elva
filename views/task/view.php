<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\EmployeeRoles;

$this->title = $model->task;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($canManageThisTask): ?>
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
    <?php endif; ?>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'task',
            [
                'attribute' => 'employee_id',
                'value' => $model->employee !== null ? $model->employee->name . ' ' . $model->employee->surname : 'Not set',
                'label' => 'Employee',
            ],
            [
                'attribute' => 'construction_site_id',
                'value' => $model->constructionSite->location,
                'label' => 'Construction Site',
            ],
            'date:date',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>
</div> 