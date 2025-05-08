<?php
namespace app\rbac;

use Yii;
use yii\rbac\Rule;

class ManageTaskRule extends Rule
{
    public $name = 'canManageTask';

    public function execute($user, $item, $params): bool
    {
        return Yii::$app->user->identity->isAdmin() || $this->hasAccess($user, $params);
    }

    private function hasAccess(string|int $user, array $params): bool
    {
        if (!isset($params['task'])) {
            return true;
        }

        $task = $params['task'];
        $constructionSite = $task->getConstructionSite()->one();
        if (empty($constructionSite)) {
            return false;
        }

        $manage = Yii::$app->user->identity->managesConstructionSite($constructionSite);
        if (empty($manage)) {
            return false;
        }

        return $manage->manager_id === $user;
    }
}