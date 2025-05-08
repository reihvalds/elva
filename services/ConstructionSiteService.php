<?php

namespace app\services;

use app\models\ConstructionSite;
use app\models\Employee;
use app\models\EmployeeRoles;
use app\models\Task;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class ConstructionSiteService
{
    public function getDataProvider(): ActiveDataProvider
    {
        $query = ConstructionSite::find();
        
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->isManager()) {
            $query->where(['manager_id' => $currentUser->id]);
        } elseif ($currentUser->isEmployee()) {
            $sitesWithTasks = Task::find()
                ->select('construction_site_id')
                ->where(['employee_id' => $currentUser->id])
                ->distinct()
                ->column();

            if (empty($sitesWithTasks)) {
                $query->where('0=1');
            } else {
                $query->where(['id' => $sitesWithTasks]);
            }
        }
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function findConstructionSite(int $id): ConstructionSite
    {
        $constructionSite = ConstructionSite::findOne(['id' => $id]);
        if ($constructionSite === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!AccessControlService::hasRole('viewOwnConstructionSites', ['constructionSite' => $constructionSite])) {
            throw new ForbiddenHttpException('You do not have permission to view this construction site.');
        }

        return $constructionSite;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function createConstructionSite(): ConstructionSite
    {
        if (!AccessControlService::hasRole('manageConstructionSites')) {
            throw new ForbiddenHttpException('You are not allowed to create construction sites.');
        }
        
        $constructionSite = new ConstructionSite();
        $constructionSite->loadDefaultValues();
        return $constructionSite;
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function deleteConstructionSite(int $id): bool
    {
        $constructionSite = $this->findConstructionSite($id);

        if (!AccessControlService::hasRole('manageConstructionSites')) {
            throw new ForbiddenHttpException('You are not allowed to delete construction sites.');
        }
        
        return $constructionSite->delete();
    }

    public function getAvailableManagers(): array
    {
        $result = [];
        
        if (Yii::$app->user->identity->isAdmin()) {
            $managersList = Employee::find()
                ->where(['role' => EmployeeRoles::ROLE_MANAGER])
                ->all();
            
            foreach ($managersList as $manager) {
                $result[$manager->id] = $manager->name . ' ' . $manager->surname;
            }
        }
        
        return $result;
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function getSiteManagerForDisplay($siteId): string
    {
        $site = $this->findConstructionSite($siteId);
        $manager = $site->manager;

        if (!$manager) {
            return 'No manager assigned';
        }

        return $manager->name . ' ' . $manager->surname;
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function getCurrentManagerId($siteId): ?int
    {
        $site = $this->findConstructionSite($siteId);
        return $site->manager_id;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function saveConstructionSite(ConstructionSite $constructionSite, array $data): bool
    {
        if (!AccessControlService::hasRole('manageConstructionSites')) {
            throw new ForbiddenHttpException('You are not allowed to create construction sites.');
        }
        
        try {
            if (!$constructionSite->load($data)) {
                return false;
            }

            $constructionSite->manager_id = $data['ConstructionSite']['managerId'] ?? null;
            
            return $constructionSite->save();
        } catch (\Exception $exception) {
            Yii::error('Error saving construction site with manager: ' . $exception->getMessage());
            return false;
        }
    }

    public function getTasksDataProviderForSite($siteId, $currentUser): ActiveDataProvider
    {
        $isEmployee = !$currentUser->isAdmin() && !$currentUser->isManager();

        $tasksQuery = Task::find()
            ->where(['construction_site_id' => $siteId]);

        if ($isEmployee) {
            $tasksQuery->andWhere(['employee_id' => $currentUser->id]);
        }

        return new ActiveDataProvider([
            'query' => $tasksQuery,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                ]
            ],
        ]);
    }
} 