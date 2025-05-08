<?php

namespace app\services;

use app\models\Task;
use app\models\Employee;
use app\models\ConstructionSite;
use app\models\EmployeeRoles;
use yii\db\StaleObjectException;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\data\ActiveDataProvider;
use Yii;

class TaskService
{
    public function getDataProvider(): ActiveDataProvider
    {
        $query = Task::find()
            ->with(['employee', 'constructionSite']);

        $currentUser = Yii::$app->user->identity;
        if ($currentUser) {
            if ($currentUser->isAdmin()) {
            } else if ($currentUser->isManager()) {
                $managedSiteIds = $currentUser->getManagedConstructionSites()->select('id')->column();
                $query->andWhere(['IN', 'construction_site_id', $managedSiteIds]);
            } else {
                $query->andWhere(['employee_id' => $currentUser->id]);
            }
        }
        
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'date' => SORT_DESC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function findTask(int $id): Task
    {
        $task = Task::findOne(['id' => $id]);
        
        if ($task === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!AccessControlService::hasRole('viewOwnTasks', ['task' => $task])) {
            throw new ForbiddenHttpException('You do not have permission to view this task.');
        }
        
        return $task;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function createTask(): Task
    {
        if (!AccessControlService::hasRole('manageTasks')) {
            throw new ForbiddenHttpException('Only administrators and managers can create tasks.');
        }
        
        $task = new Task();
        $task->loadDefaultValues();
        return $task;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function saveTask($task, $data): bool
    {
        if (!AccessControlService::hasRole('manageTasks', ['task' => $task])) {
            throw new ForbiddenHttpException('Only administrators and managers can manage tasks.');
        }
        
        if ($task->load($data)) {
            if ($task->save()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function deleteModel($id): bool
    {
        $task = $this->findTask($id);

        if (!AccessControlService::hasRole('manageTasks', ['task' => $task])) {
            throw new ForbiddenHttpException('Only administrators and managers can delete tasks.');
        }
        
        return $task->delete();
    }

    public function getEmployeesList(): array
    {
        $query = Employee::find()
            ->where(['role' => EmployeeRoles::ROLE_EMPLOYEE]);
            
        $currentUser = Yii::$app->user->identity;

        if ($currentUser->isManager()) {
            $subordinateIds = $currentUser->getAllSubordinates();
            if (!empty($subordinateIds)) {
                $query->andWhere(['IN', 'id', $subordinateIds]);
            }
        }
        
        $employees = $query->all();

        $list = [];
        foreach ($employees as $employee) {
            $list[$employee->id] = sprintf('%s %s', $employee->name, $employee->surname);
        }

        return $list;
    }

    public function getConstructionSitesList(): array
    {
        $query = ConstructionSite::find();
        $currentUser = Yii::$app->user->identity;
        
        if ($currentUser && !$currentUser->isAdmin()) {
            if ($currentUser->isManager()) {
                $managedSiteIds = $currentUser->getManagedConstructionSites()->select('id')->column();
                if (empty($managedSiteIds)) {
                    $query->where('0=1');
                } else {
                    $query->andWhere(['IN', 'id', $managedSiteIds]);
                }
            } else {
                $query->where('0=1');
            }
        }
        
        return $query->select(['location', 'id'])
            ->indexBy('id')
            ->column();
    }
} 