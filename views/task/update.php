<?php

use yii\helpers\Html;

$this->title = 'Update Task: ' . $model->task;
$this->params['breadcrumbs'][] = ['label' => 'Tasks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->task, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="task-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'employeesList' => $employeesList,
        'constructionSitesList' => $constructionSitesList,
    ]) ?>
</div> 