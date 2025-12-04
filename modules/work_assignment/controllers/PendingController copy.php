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
class PendingController extends Controller
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
        $searchModel = new KpiWorkRegisteredSearch();
  		if(isset($_POST['search']) && $_POST['search'] != null){
            $dataProvider = $searchModel->search(Yii::$app->request->post(), $_POST['search']);
        } else if ($searchModel->load(Yii::$app->request->post())) {
            $searchModel = new KpiWorkRegisteredSearch(); // "reset"
            $dataProvider = $searchModel->search(Yii::$app->request->post());
        } else {
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        }    
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Xem",
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::a('Duyệt',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);   

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

             // --- Hiển thị form lần đầu ---
            if($request->isGet){
                return [
                    'title'=> "Duyệt",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }



        }

       /*  $model = KpiWorkRegisteredForm::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Không tìm thấy công việc #$id");
        }

        $jobsDataProvider = new ActiveDataProvider([
            'query' => KpiWorkAssignment::find()->where(['work_registered_id' => $id]),
            'pagination' => false,
        ]);

        if (Yii::$app->request->isPost) {
            $selectedJobs = Yii::$app->request->post('selectedJobs', []);
            // Duyệt các công việc đã chọn
            if ($selectedJobs) {
                KpiWorkAssignment::updateAll(['status_id' => KpiWorkAssignmentStatus::APPROVED], ['id' => $selectedJobs]);
                Yii::$app->session->setFlash('success', 'Duyệt thành công!');
                return $this->redirect(['index']); // hoặc PJAX reload 
            }
        }

        return $this->render('update', [
            'model' => $model,
            'jobsDataProvider' => $jobsDataProvider,
        ]);
     */
    }

    protected function findModel($id)
    {
        $model = KpiWorkRegistered::findOne($id); // đổi theo model của bạn
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException("Không tìm thấy công việc #$id");
        }
        return $model;
    }


} // end class