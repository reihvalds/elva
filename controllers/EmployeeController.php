<?php

namespace app\controllers;

use app\models\EmployeeRoles;
use app\services\AccessControlService;
use app\services\EmployeeService;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class EmployeeController extends Controller
{
    private EmployeeService $employeeService;

    public function __construct(
        $id, 
        $module, 
        EmployeeService $employeeService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->employeeService = $employeeService;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'matchCallback' => function ($rule, $action) {
                            return AccessControlService::hasRole('manageEmployees');
                        }
                    ],
                ],
                'denyCallback' => function ($rule, $action) {
                    throw new ForbiddenHttpException('You do not have permission to perform this action.');
                }
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex(): string
    {
        $dataProvider = $this->employeeService->getDataProvider();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'canManageEmployees' => AccessControlService::hasRole('manageEmployees'),
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $employee = $this->employeeService->findEmployee($id);
        $manager = null;
        
        if ($employee->role === EmployeeRoles::ROLE_EMPLOYEE) {
            $manager = $this->employeeService->getEmployeeManager($id);
        }
        
        return $this->render('view', [
            'model' => $employee,
            'canManageEmployees' => AccessControlService::hasRole('manageEmployees'),
            'manager' => $manager,
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionCreate(): Response|string
    {
        $employee = $this->employeeService->createModel();
        $managers = $this->employeeService->getManagersList(null);

        if ($this->request->isPost && $this->employeeService->saveEmployee($employee, $this->request->post())) {
            return $this->redirect(['view', 'id' => $employee->id]);
        }

        return $this->render('create', [
            'model' => $employee,
            'managers' => $managers,
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $employee = $this->employeeService->findEmployee($id);
        $managers = $this->employeeService->getManagersList($id);

        if ($this->request->isPost && $this->employeeService->saveEmployee($employee, $this->request->post())) {
            return $this->redirect(['view', 'id' => $employee->id]);
        }

        return $this->render('update', [
            'model' => $employee,
            'managers' => $managers,
        ]);
    }

    /**
     * @throws \Throwable
     * @throws StaleObjectException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->employeeService->deleteEmployee($id);
        return $this->redirect(['index']);
    }
} 