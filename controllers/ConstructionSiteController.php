<?php

namespace app\controllers;

use app\services\ConstructionSiteService;
use app\services\AccessControlService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ConstructionSiteController extends Controller
{
    private ConstructionSiteService $constructionSiteService;

    public function __construct(
        $id, 
        $module, 
        ConstructionSiteService $constructionSiteService,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
        $this->constructionSiteService = $constructionSiteService;
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
                            return AccessControlService::hasRole('manageConstructionSites');
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
        $dataProvider = $this->constructionSiteService->getDataProvider();

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'canManageConstructionSites' => AccessControlService::hasRole('manageConstructionSites'),
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionView(int $id): string
    {
        $constructionSite = $this->constructionSiteService->findConstructionSite($id);
        $managers = $this->constructionSiteService->getSiteManagerForDisplay($id);
        $currentUser = \Yii::$app->user->identity;
        $tasksDataProvider = $this->constructionSiteService->getTasksDataProviderForSite($id, $currentUser);
        
        return $this->render('view', [
            'model' => $constructionSite,
            'managers' => $managers,
            'isEmployee' => $currentUser->isEmployee(),
            'tasksDataProvider' => $tasksDataProvider,
            'canManageConstructionSites' => AccessControlService::hasRole('manageConstructionSites'),
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionCreate(): Response|string
    {
        $constructionSite = $this->constructionSiteService->createConstructionSite();

        if ($this->request->isPost && $this->constructionSiteService->saveConstructionSite($constructionSite, $this->collectPostData())) {
            return $this->redirect(['view', 'id' => $constructionSite->id]);
        }

        return $this->render('create', [
            'model' => $constructionSite,
            'managers' => $this->constructionSiteService->getAvailableManagers(),
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    public function actionUpdate(int $id): Response|string
    {
        $constructionSite = $this->constructionSiteService->findConstructionSite($id);

        if ($this->request->isPost && $this->constructionSiteService->saveConstructionSite($constructionSite, $this->collectPostData())) {
            return $this->redirect(['view', 'id' => $constructionSite->id]);
        }

        return $this->render('update', [
            'model' => $constructionSite,
            'managers' => $this->constructionSiteService->getAvailableManagers(),
            'currentManagerId' => $this->constructionSiteService->getCurrentManagerId($id),
        ]);
    }

    /**
     * @throws ForbiddenHttpException
     */
    public function actionDelete(int $id): Response
    {
        $this->constructionSiteService->deleteConstructionSite($id);
        return $this->redirect(['index']);
    }

    private function collectPostData(): array
    {
        $constructionSiteData = $this->request->post('ConstructionSite', []);
        $constructionSiteData['managerId'] = $this->request->post('managerId');
        return [
            'ConstructionSite' => $constructionSiteData,
        ];
    }
    
} 