<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use app\models\EmployeeRoles;
use yii\helpers\ArrayHelper;

/** @var yii\web\View $this */
/** @var app\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
/** @var array $managers List of available managers */
?>

<div class="employee-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'birthday')->input('date') ?>

    <?= $form->field($model, 'role')->dropDownList(EmployeeRoles::getRolesList()) ?>

    <?= $form->field($model, 'access_level')->dropDownList([
        1 => 'Level 1',
        2 => 'Level 2',
        3 => 'Level 3',
    ]) ?>

    <div class="form-group field-manager_id" style="display:none;">
        <?= $form->field($model, 'manager_id')->dropDownList(
            $managers ?? [], 
            [
                'prompt' => '-- Select Manager --'
            ]
        ) ?>
    </div>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <?= $form->field($model, 'password_repeat')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$employeeRole = EmployeeRoles::ROLE_EMPLOYEE;
$js = <<<JS
$(document).ready(function() {
    $('#employee-role').on('change', function() {
        if ($(this).val() === '{$employeeRole}') {
            $('.field-manager_id').show();
        } else {
            $('#employee-manager_id').val('');
            $('.field-manager_id').hide();
        }
    });
    
    $('#employee-role').trigger('change');
});
JS;
$this->registerJs($js);
?> 