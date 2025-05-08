<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */

$this->title = 'Update Employee: ' . $model->name . ' ' . $model->surname;
$this->params['breadcrumbs'][] = ['label' => 'Employees', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name . ' ' . $model->surname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="employee-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'managers' => $managers,
    ]) ?>
</div> 