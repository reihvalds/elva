<?php

namespace app\services;

use app\models\Employee;
use app\models\EmployeeRoles;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class EmployeeService
{
    public function getDataProvider(): ActiveDataProvider
    {
        $query = Employee::find();

        $currentUser = Yii::$app->user->identity;
        if ($currentUser->isManager()) {
            $query->where(['manager_id' => $currentUser->id]);
        } elseif ($currentUser->isEmployee()) {
            $query->where(['id' => $currentUser->id]);
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
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function findEmployee(int $id): Employee
    {
        $employee = Employee::findOne(['id' => $id]);
        
        if ($employee === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!AccessControlService::hasRole('viewOwnEmployees', ['employee' => $employee])) {
            throw new ForbiddenHttpException('You do not have permission to view this employee.');
        }
        
        return $employee;
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function createModel(): Employee
    {
        if (!AccessControlService::hasRole('manageEmployees')) {
            throw new ForbiddenHttpException('You do not have permission to create employees.');
        }
        
        $employee = new Employee();
        $employee->loadDefaultValues();
        return $employee;
    }

    /**
     * @throws ForbiddenHttpException
     * @throws \Exception
     */
    public function saveEmployee($employee, $data): bool
    {
        if (!AccessControlService::hasRole('manageEmployees')) {
            throw new ForbiddenHttpException('You do not have permission to manage employees.');
        }

        $isNewRecord = $employee->isNewRecord;
        $oldRole = $isNewRecord ? null : $employee->role;
        if ($employee->load($data) && $employee->save()) {

            $this->updateRole($employee);

            if (!$isNewRecord && $oldRole === EmployeeRoles::ROLE_EMPLOYEE &&
                ($employee->role === EmployeeRoles::ROLE_ADMIN || $employee->role === EmployeeRoles::ROLE_MANAGER)) {
                $this->removeManager($employee);
            }

            if ($isNewRecord && !Yii::$app->user->identity->isAdmin()) {
                $currentUser = Yii::$app->user->identity;
                if ($currentUser->isManager() && $employee->role === EmployeeRoles::ROLE_EMPLOYEE) {
                    $currentUser->addSubordinate($employee);
                }
            }
            return true;
        }
        return false;
    }

    private function removeManager($employee): void
    {
        $employee->manager_id = null;
        $employee->save(false);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function deleteEmployee(int $id): bool
    {
        if (!AccessControlService::hasRole('manageEmployees')) {
            throw new ForbiddenHttpException('You do not have permission to delete employees.');
        }
        
        $employee = $this->findEmployee($id);
        
        $currentUser = Yii::$app->user->identity;
        
        if ($currentUser->isAdmin() && $currentUser->id === $employee->id) {
            throw new ForbiddenHttpException('Administrators cannot delete their own accounts.');
        }
        
        return $employee->delete();
    }
    
    public function getManagersList(?int $id): array
    {
        $managers = Employee::find()
            ->where(['role' => EmployeeRoles::ROLE_MANAGER]);

        if ($id !== null) {
            $managers->andWhere(['!=', 'id', $id]);
        }

        $managers = $managers->all();
            
        $result = [];
        foreach ($managers as $manager) {
            $result[$manager->id] = $manager->name . ' ' . $manager->surname;
        }
        
        return $result;
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException|Exception
     */
    public function assignSubordinate($managerId, $subordinateId): bool
    {
        $manager = $this->findEmployee($managerId);
        $subordinate = $this->findEmployee($subordinateId);

        return $manager->addSubordinate($subordinate);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException|Exception
     */
    public function removeSubordinate($managerId, $subordinateId): bool
    {
        $manager = $this->findEmployee($managerId);
        $subordinate = $this->findEmployee($subordinateId);
        
        return $manager->removeSubordinate($subordinate);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function getEmployeeManager($employeeId): ?ActiveRecord
    {
        $employee = $this->findEmployee($employeeId);
        return $employee->getManager()->one();
    }

    /**
     * @throws \Exception
     */
    private function updateRole(Employee $employee): void
    {
        $auth = \Yii::$app->authManager;
        $auth->revokeAll($employee->getId());

        $authRole = $auth->getRole($employee->role);
        $auth->assign($authRole, $employee->getId());
    }
} 