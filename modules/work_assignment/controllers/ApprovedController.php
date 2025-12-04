<?php

namespace app\modules\work_assignment\controllers;

use app\models\KpiWorkAssignment;
use app\models\KpiWorkAssignmentStatus;
use app\models\KpiWorkRegistered;
use Yii;
use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for KpiWorkAssignmentForm model.
 */
class ApprovedController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
    		return [
    			'ghost-access'=> [
    			'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        		],
    			'verbs' => [
    				'class' => VerbFilter::className(),
    				'actions' => [
    					'delete' => ['POST'],
    				],
    			],
		];
	}

}