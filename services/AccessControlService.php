<?php

namespace app\services;

use app\models\ConstructionSite;
use app\models\Employee;
use app\models\EmployeeRoles;
use app\models\Task;
use Yii;

class AccessControlService
{
    public function isAdmin(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        
        return Yii::$app->user->identity->role === EmployeeRoles::ROLE_ADMIN;
    }
    
    public function isManager(): bool
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        
        return Yii::$app->user->identity->role === EmployeeRoles::ROLE_MANAGER;
    }

    public static function hasRole(string $role, array $params = []): bool
    {
        $user = Yii::$app->user;
        return $user->can($role, $params);
    }
    
    public function checkEmployeeAccess(int $employeeId, int $constructionSiteId, string $date): bool
    {
        $employee = Employee::findOne($employeeId);
        if (!$employee) {
            return false;
        }

        $constructionSite = ConstructionSite::findOne($constructionSiteId);
        if (!$constructionSite) {
            return false;
        }

        if ($employee->isAdmin()) {
            return true;
        }

        if ($employee->isManager() && $employee->managesConstructionSite($constructionSite)) {
            return true;
        }

        return Task::find()
            ->where([
                'employee_id' => $employeeId,
                'construction_site_id' => $constructionSiteId,
                'date' => $date,
            ])
            ->exists();
    }
} 