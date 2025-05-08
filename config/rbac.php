<?php

use yii\rbac\DbManager;
use app\models\EmployeeRoles;

require_once __DIR__ . '/../models/EmployeeRoles.php';

return [
    'class' => DbManager::class,
    'defaultRoles' => [
    ],
]; 