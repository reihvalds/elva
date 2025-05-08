<?php
namespace app\rbac;

use app\models\Task;
use Yii;
use yii\rbac\Rule;

class ViewTaskRule extends Rule
{
    public $name = 'canViewTask';

    public function execute($user, $item, $params): bool
    {
        return Yii::$app->user->identity->isAdmin()
            || (isset($params['task']) && $this->hasAccess($user, $params['task']));
    }

    private function hasAccess(string|int $user, Task $task): bool
    {
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->isEmployee() && $currentUser->id === $task->employee_id) {
            return true;
        }

        $constructionSite = $task->getConstructionSite()->one();
        if (empty($constructionSite)) {
            return false;
        }
        $manage = Yii::$app->user->identity->managesConstructionSite($constructionSite);
        if (!empty($manage) && $manage->manager_id === $user) {
            return true;
        }

        return false;
    }
}