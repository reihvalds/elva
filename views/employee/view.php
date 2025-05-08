<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\EmployeeRoles;

$this->title = $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($canManageEmployees): ?>
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
            'name',
            'surname',
            'birthday',
            'access_level',
            [
                'label' => 'Manager',
                'value' => $manager ? $manager->name . ' ' . $manager->surname : 'None',
                'visible' => $model->role === EmployeeRoles::ROLE_EMPLOYEE,
            ],
            'role',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div> 