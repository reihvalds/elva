<?php

namespace app\controllers;

use app\services\AccessControlService;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class ApiController extends Controller
{
    private $accessControlService;

    public function __construct($id, $module, AccessControlService $accessControlService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->accessControlService = $accessControlService;
    }

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'check-employee-access' => ['GET'],
                ],
            ],
        ];
    }

    public function init(): void
    {
        parent::init();
        Yii::$app->response->format = Response::FORMAT_JSON;
    }

    public function actionCheckEmployeeAccess(): array
    {
        $request = Yii::$app->request;
        
        $employeeId = $request->get('employee_id');
        $constructionSiteId = $request->get('construction_site_id');
        $date = $request->get('date');
        
        if (!$employeeId || !$constructionSiteId || !$date) {
            return [
                'success' => false,
                'message' => 'Missing required parameters. Please provide employee_id, construction_site_id, and date.',
            ];
        }
        
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
            return [
                'success' => false,
                'message' => 'Invalid date format. Please use YYYY-MM-DD format.',
            ];
        }
        
        try {
            $hasAccess = $this->accessControlService->checkEmployeeAccess(
                (int) $employeeId,
                (int) $constructionSiteId,
                $date
            );
            
            return [
                'success' => true,
                'has_access' => $hasAccess,
                'employee_id' => $employeeId,
                'construction_site_id' => $constructionSiteId,
                'date' => $date,
            ];
        } catch (\Exception $exception) {
            // Log exception somewhere - for example sentry
            return [
                'success' => false,
                'message' => 'An error occurred while checking access',
            ];
        }
    }
} 