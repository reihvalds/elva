<?php

use yii\helpers\Html;

$this->title = 'Create Construction Site';
$this->params['breadcrumbs'][] = ['label' => 'Construction Sites', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="construction-site-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'managers' => $managers,
        'currentManagerId' => null,
    ]) ?>
</div> 