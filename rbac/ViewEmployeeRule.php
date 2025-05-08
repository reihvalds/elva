<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class ViewEmployeeRule extends Rule
{
    public $name = 'canViewEmployee';

    public function execute($user, $item, $params): bool
    {
        return Yii::$app->user->identity->isAdmin() || $this->hasAccess($user, $params);
    }

    private function hasAccess(string|int $user, array $params): bool
    {
        $employee = $params['employee'];
        $currentUser = Yii::$app->user->identity;

        if (!$currentUser) {
            return false;
        }

        if ($currentUser->isEmployee() && $currentUser->id === $employee->id) {
            return true;
        }

        if ($currentUser->isManager() && $currentUser->isSubordinate($employee)) {
            return true;
        }

        return false;
    }
}