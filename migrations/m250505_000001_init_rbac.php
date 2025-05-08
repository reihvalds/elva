<?php

use app\rbac\ManageTaskRule;
use app\rbac\ViewConstructionSiteRule;
use app\rbac\ViewEmployeeRule;
use app\rbac\ViewTaskRule;
use yii\db\Migration;

class m250505_000001_init_rbac extends Migration
{
    /**
     * @throws \yii\base\Exception
     * @throws Exception
     */
    public function up(): void
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        
        $viewEmployees = $auth->createPermission('viewEmployees');
        $viewEmployees->description = 'View employees';
        $viewEmployees->data = serialize(null);
        $auth->add($viewEmployees);

        $viewEmployeeRule = new ViewEmployeeRule();
        $auth->add($viewEmployeeRule);

        $viewOwnEmployees = $auth->createPermission('viewOwnEmployees');
        $viewOwnEmployees->description = 'View own employees';
        $viewOwnEmployees->data = serialize(null);
        $viewOwnEmployees->ruleName = $viewEmployeeRule->name;
        $auth->add($viewOwnEmployees);
        $auth->addChild($viewOwnEmployees, $viewEmployees);

        $manageEmployees = $auth->createPermission('manageEmployees');
        $manageEmployees->description = 'Manage employees';
        $manageEmployees->data = serialize(null);
        $auth->add($manageEmployees);

        $viewConstructionSites = $auth->createPermission('viewConstructionSites');
        $viewConstructionSites->description = 'View construction sites';
        $viewConstructionSites->data = serialize(null);
        $auth->add($viewConstructionSites);

        $viewConstructionSiteRule = new ViewConstructionSiteRule();
        $auth->add($viewConstructionSiteRule);

        $viewOwnConstructionSites = $auth->createPermission('viewOwnConstructionSites');
        $viewOwnConstructionSites->description = 'View own construction sites';
        $viewOwnConstructionSites->data = serialize(null);
        $viewOwnConstructionSites->ruleName = $viewConstructionSiteRule->name;
        $auth->add($viewOwnConstructionSites);
        $auth->addChild($viewOwnConstructionSites, $viewConstructionSites);

        $manageConstructionSites = $auth->createPermission('manageConstructionSites');
        $manageConstructionSites->description = 'Manage construction sites';
        $manageConstructionSites->data = serialize(null);
        $auth->add($manageConstructionSites);

        $viewTasks = $auth->createPermission('viewTasks');
        $viewTasks->description = 'View tasks';
        $viewTasks->data = serialize(null);
        $auth->add($viewTasks);

        $viewTaskRule = new ViewTaskRule;
        $auth->add($viewTaskRule);

        $viewOwnTasks = $auth->createPermission('viewOwnTasks');
        $viewOwnTasks->description = 'View own tasks';
        $viewOwnTasks->data = serialize(null);
        $viewOwnTasks->ruleName = $viewTaskRule->name;
        $auth->add($viewOwnTasks);
        $auth->addChild($viewOwnTasks, $viewTasks);

        $manageTasks = $auth->createPermission('manageTasks');
        $manageTasks->description = 'Manage tasks';
        $manageTasks->data = serialize(null);
        $auth->add($manageTasks);

        $manageTaskRule = new ManageTaskRule();
        $auth->add($manageTaskRule);

        $manageOwnTasks = $auth->createPermission('manageOwnTasks');
        $manageOwnTasks->description = 'Manage own tasks';
        $manageOwnTasks->data = serialize(null);
        $manageOwnTasks->ruleName = $manageTaskRule->name;
        $auth->add($manageOwnTasks);
        $auth->addChild($manageOwnTasks, $manageTasks);

        // Create roles
        $employee = $auth->createRole('employee');
        $employee->data = serialize(null);
        $auth->add($employee);
        $auth->addChild($employee, $viewEmployees);
        $auth->addChild($employee, $viewOwnEmployees);
        $auth->addChild($employee, $viewConstructionSites);
        $auth->addChild($employee, $viewOwnConstructionSites);
        $auth->addChild($employee, $viewTasks);
        $auth->addChild($employee, $viewOwnTasks);

        $manager = $auth->createRole('manager');
        $manager->data = serialize(null);
        $auth->add($manager);
        $auth->addChild($manager, $employee);
        $auth->addChild($manager, $manageTasks);
        $auth->addChild($manager, $manageOwnTasks);

        $admin = $auth->createRole('admin');
        $admin->data = serialize(null);
        $auth->add($admin);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $manageConstructionSites);
        $auth->addChild($admin, $manageEmployees);
    }
    
    public function down(): void
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
    }
}