<?php

namespace app\commands;

use yii\console\Controller;
use app\models\Employee;
use app\models\ConstructionSite;
use app\models\Task;
use app\models\EmployeeRoles;
use yii\rbac\DbManager;

class SeedController extends Controller
{
    private int $managerId;

    public function actionIndex(): void
    {
        $this->stdout('Seeding database...\n');

        $auth = \Yii::$app->authManager;
        
        $this->seedEmployees($auth);
        $this->seedConstructionSites($auth);
        $this->seedTasks($auth);

        $this->stdout('Database seeded successfully.\n');
    }

    private function seedEmployees(DbManager $auth): void
    {
        $adminRole = $auth->getRole(EmployeeRoles::ROLE_ADMIN);
        $managerRole = $auth->getRole(EmployeeRoles::ROLE_MANAGER);
        $employeeRole = $auth->getRole(EmployeeRoles::ROLE_EMPLOYEE);

        if (!$adminRole || !$managerRole || !$employeeRole) {
            $this->stdout("RBAC roles not found. Make sure you've run the RBAC migration.\n");
            return;
        }

        Employee::deleteAll();

        $admin = new Employee();
        $admin->name = 'admin';
        $admin->surname = 'Admin';
        $admin->birthday = date('Y-m-d', strtotime('-30 years'));
        $admin->role = EmployeeRoles::ROLE_ADMIN;
        $admin->password = 'admin123';
        if (!$admin->save()) {
            $this->stdout("Failed to save admin: " . print_r($admin->getErrors(), true) . "\n");
            return;
        }
        $adminId = $admin->getId();
        if (!$adminId) {
            $this->stdout("Failed to get admin ID after save\n");
            return;
        }
        if (!$auth->assign($adminRole, $adminId)) {
            $this->stdout("Failed to assign admin role\n");
            return;
        }

        $manager = new Employee();
        $manager->name = 'manager';
        $manager->surname = 'Manager';
        $manager->birthday = date('Y-m-d', strtotime('-35 years'));
        $manager->role = EmployeeRoles::ROLE_MANAGER;
        $manager->password = 'manager123';
        if (!$manager->save()) {
            $this->stdout("Failed to save manager: " . print_r($manager->getErrors(), true) . "\n");
            return;
        }
        $this->managerId = $manager->getId();
        if (!$this->managerId) {
            $this->stdout("Failed to get manager ID after save\n");
            return;
        }
        if (!$auth->assign($managerRole, $this->managerId)) {
            $this->stdout("Failed to assign manager role\n");
            return;
        }

        $employee = new Employee();
        $employee->name = 'employee';
        $employee->surname = 'Worker';
        $employee->birthday = date('Y-m-d', strtotime('-25 years'));
        $employee->role = EmployeeRoles::ROLE_EMPLOYEE;
        $employee->password = 'employee123';
        $employee->manager_id = $this->managerId;
        if (!$employee->save()) {
            $this->stdout("Failed to save employee: " . print_r($employee->getErrors(), true) . "\n");
            return;
        }
        $employeeId = $employee->getId();
        if (!$employeeId) {
            $this->stdout("Failed to get employee ID after save\n");
            return;
        }
        if (!$auth->assign($employeeRole, $employeeId)) {
            $this->stdout("Failed to assign employee role\n");
            return;
        }
    }

    private function seedConstructionSites(DbManager $auth): void
    {
        ConstructionSite::deleteAll();

        for ($i = 0; $i < 3; $i++) {
            $constructionSite = new ConstructionSite();
            $constructionSite->location = 'Construction Site ' . ($i + 1);
            $constructionSite->quadrature = rand(100, 1000);
            $constructionSite->access_level = rand(1, 3);
            $constructionSite->manager_id = $this->managerId;
            $constructionSite->save();
        }
    }

    private function seedTasks(DbManager $auth): void
    {
        Task::deleteAll();

        $employees = Employee::find()
            ->where(['role' => EmployeeRoles::ROLE_EMPLOYEE])
            ->all();
        $constructionSites = ConstructionSite::find()->all();
        
        foreach ($employees as $employee) {
            foreach ($constructionSites as $constructionSite) {
                $task = new Task();
                $task->task = 'Task for ' . $employee->name . ' at ' . $constructionSite->location;
                $task->employee_id = $employee->id;
                $task->construction_site_id = $constructionSite->id;
                $task->date = date('Y-m-d');
                
                if (!$task->save()) {
                    $this->stdout("Failed to save task: " . print_r($task->getErrors(), true) . "\n");
                }
            }
        }
    }
}