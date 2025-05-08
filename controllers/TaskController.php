<?php

namespace app\controllers;

use app\services\AccessControlService;
use app\services\TaskService;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TaskController extends Controller
{
    private $taskService;

    public function __construct($id, $module, TaskService $taskService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->taskService = $taskService;
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
                            return Yii::$app->user->can('manageTasks');
                        },
                    ],
                ],
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
        $dataProvider = $this->taskService->getDataProvider();
        $canManageTasks = AccessControlService::hasRole('manageTasks');

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'canManageTasks' => $canManageTasks,
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView(int $id): string
    {
        $task = $this->taskService->findTask($id);
        return $this->render('view', [
            'model' => $task,
            'canManageThisTask' => AccessControlService::hasRole('manageTasks', ['task' => $task]),
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionCreate(): Response|string
    {
        $task = $this->taskService->createTask();

        if ($this->request->isPost) {
            if ($this->taskService->saveTask($task, $this->request->post())) {
                return $this->redirect(['view', 'id' => $task->id]);
            }
        }

        return $this->render('create', [
            'model' => $task,
            'employeesList' => $this->taskService->getEmployeesList(),
            'constructionSitesList' => $this->taskService->getConstructionSitesList(),
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $task = $this->taskService->findTask($id);

        if ($this->request->isPost && $this->taskService->saveTask($task, $this->request->post())) {
            return $this->redirect(['view', 'id' => $task->id]);
        }

        return $this->render('update', [
            'model' => $task,
            'employeesList' => $this->taskService->getEmployeesList(),
            'constructionSitesList' => $this->taskService->getConstructionSitesList(),
        ]);
    }

    /**
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->taskService->deleteModel($id);
        return $this->redirect(['index']);
    }
} 