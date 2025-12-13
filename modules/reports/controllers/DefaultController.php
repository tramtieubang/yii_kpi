<?php

namespace app\modules\reports\controllers;

use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `reports` module
 */
class DefaultController extends Controller
{
    public function behaviors() {
    		return [
    			'ghost-access'=> [
    			'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        		],
    			'verbs' => [
    				'class' => VerbFilter::class,
    				'actions' => [
    					'delete' => ['POST'],
                        'history-delete' => ['POST'], // add this
    				],
    			],
		];
	}

    /**
     * Renders the index view for the module
     * @return string
     */

    public function actionIndex()
    {
        
        $searchModel = new KpiWorkRegisteredSearch();
    
        if (isset($_POST['search']) && $_POST['search'] !== null) {
            $dataProvider = $searchModel->search(Yii::$app->request->post(), $_POST['search']);
        } elseif ($searchModel->load(Yii::$app->request->post())) {
            $searchModel = new KpiWorkRegisteredSearch(); // reset
            $dataProvider = $searchModel->search(Yii::$app->request->post()); 
        } else { // khoi tao
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }
  
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }
}
