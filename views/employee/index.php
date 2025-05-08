<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Url;
use app\models\EmployeeRoles;

$this->title = 'Employees';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($canManageEmployees): ?>
    <p>
        <?= Html::a('Create Employee', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php endif; ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'surname',
            'birthday',
            'access_level',
            'role',
            [
                'class' => ActionColumn::class,
                'template' => $canManageEmployees ? '{view} {update} {delete}' : '{view}',
                'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div> 