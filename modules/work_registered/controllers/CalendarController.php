<?php

namespace app\modules\work_registered\controllers;

use app\modules\employees\models\EmployeesForm;
use Yii;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * RegisterController implements the Controller.
 */
class CalendarController extends Controller
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

    /**
     * Lists all KpiWorkRegisteredForm models.
     * @return mixed
     */

    public function actionIndex()
    {
         $model = EmployeesForm::find()
        ->select(['id', 'name'])
        ->indexBy('id')
        ->column();

        //return $this->render('@app/modules/work_registered/views/default/calendar.php', [
        return $this->render('calendar', [
            'model' => $model
        ]);        
        //return $this->render('register');
    }

    public function actionCalendar()
    {
        return $this->render('calendar');
    }


}