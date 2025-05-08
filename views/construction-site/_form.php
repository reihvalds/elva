<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\ConstructionSite $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $managers */
/** @var int|null $currentManagerId */
?>

<div class="construction-site-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'location')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'quadrature')->textInput(['type' => 'number', 'step' => '0.01']) ?>

    <?= $form->field($model, 'access_level')->dropDownList([
        1 => 'Level 1',
        2 => 'Level 2',
        3 => 'Level 3',
    ]) ?>

    <?php if (Yii::$app->user->identity->isAdmin() && !empty($managers)): ?>
        <div class="form-group field-manager mb-3">
            <label class="form-label" for="manager-id">Assign Manager</label>
            <?= Html::dropDownList('managerId', $currentManagerId, $managers, [
                'class' => 'form-control form-select',
                'id' => 'manager-id',
                'prompt' => '-- Select Manager --'
            ]) ?>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div> 