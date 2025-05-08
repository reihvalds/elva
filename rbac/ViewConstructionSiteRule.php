<?php
namespace app\rbac;

use app\models\Task;
use Yii;
use yii\rbac\Rule;

class ViewConstructionSiteRule extends Rule
{
    public $name = 'canViewOwnConstructionSite';

    public function execute($user, $item, $params): bool
    {
        return Yii::$app->user->identity->isAdmin() || $this->hasAccess($user, $params);
    }

    private function hasAccess(string|int $user, array $params): bool
    {
        $constructionSite = $params['constructionSite'];
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->isManager() && $constructionSite->manager_id === $currentUser->id) {
            return true;
        }

        return Task::find()
            ->where([
                'employee_id' => $currentUser->id,
                'construction_site_id' => $constructionSite->id
            ])
            ->exists();
    }
}