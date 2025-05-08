<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Task $model */
/** @var array $employeesList */
/** @var array $constructionSitesList */
?>

<div class="task-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'task')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'employee_id')->dropDownList($employeesList, [
        'prompt' => 'Select Employee'
    ]) ?>

    <?= $form->field($model, 'construction_site_id')->dropDownList($constructionSitesList, [
        'prompt' => 'Select Construction Site'
    ]) ?>

    <?= $form->field($model, 'date')->input('date') ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 