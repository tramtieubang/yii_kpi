<?php

namespace app\modules\work_registered\controllers;

use app\common\helpers\CommonSQL;
use app\models\KpiWorkRegisteredHistory;
use app\models\KpiWorkRegisteredStatus;
use Yii;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * DefaultController implements the CRUD actions for KpiWorkRegisteredForm model.
 */
class DefaultController extends Controller
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
    				'class' => VerbFilter::class,
    				'actions' => [
    					'delete' => ['POST'],
                        'history-delete' => ['POST'], // add this
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

    public function actionIndex1()
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


    /**
     * Displays a single KpiWorkRegisteredForm model.
     * @param integer $id
     * @return mixed
     */
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
                            Html::a('Sửa',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionFilter()
    {
        $searchModel = new KpiWorkRegisteredSearch();

        // Lấy dữ liệu POST
        $postData = Yii::$app->request->post();

        // Truyền dữ liệu POST vào search model
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);

        /* @var \yii\db\ActiveQuery $query */ // giúp IDE nhận dạng
    /*  $query = $dataProvider->query;

        $sql = $query->createCommand()->getRawSql();

        // ========================
        // DEBUG SQL: in ra trình duyệt
        echo "<pre>";
        echo "SQL= ".$query->createCommand()->getRawSql(); // SQL thực thi
        echo "</pre>";

        Yii::info(print_r($dataProvider->getModels(), true), __METHOD__); */

        // ========================
        
        // Nếu muốn debug dữ liệu thực tế:
        // echo "<pre>";
        // print_r($dataProvider->getModels());
        // echo "</pre>";

        // Dừng script khi debug
        // exit;

        // Render view cùng searchModel và dataProvider
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            //'sql' => $sql,
        ]);
    }

    /**
     * Creates a new KpiWorkRegisteredForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($staff_id = null)
    {
        $request = Yii::$app->request;
        $model = new KpiWorkRegisteredForm();  

        // Gán staff_id mặc định nếu có
        if ($staff_id !== null) {
            $model->staff_id = $staff_id;
        }

        //$model->staff_id = 8;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Thêm mới",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                // Neu trang thai la duyet
                // Luu them vao du lieu lich chinh thuc
                if($model->status_id == 2) {
                    //$this->approve($model->id);
                    CommonSQL::approve($model->id);
                }

                return [
                    'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                    //'forceReload'=>'#crud-datatable-pjax', // PJAX Grid cha
                    'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                    'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
                    'forceClose' => true,
                    'tcontent' => 'Thêm mới thành công!'
                ];
               /*  return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Thêm mới",
                    'content'=>'<span class="text-success">Thêm mới thành công</span>',
                    'tcontent'=>'Thêm mới thành công!',
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::a('Tiếp tục thêm',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];     */     
            }else{           
                return [
                    'title'=> "Thêm mới",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'tcontent'=>Html::errorSummary($model),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing KpiWorkRegisteredForm model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Cập nhật",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
            	if(Yii::$app->params['showView']){
                    return [
                        //'forceReload'=>'#crud-datatable-pjax',
                        'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                        'title'=> "Cập nhật",
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'tcontent'=>'Cập nhật thành công!',
                        'system_id' => $model->id, // đây là key quan trọng
                        'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::a('Sửa',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }else{
                    return [
                        'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                        //'forceReload'=>'#crud-datatable-pjax', // PJAX Grid cha
                        'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                        'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
                        'forceClose' => true,
                        'tcontent' => 'Cập nhật thành công!'
                    ];

                	/* return [
                        //'forceClose'=>true, 
                        'forceReload'=>'#crud-datatable-pjax',
                        'staff_id' => $model->staff_id, // đây là key quan trọng
                        'tcontent'=>'Cập nhật thành công!',
                    ]; */
                }
            }else{
                 return [                    
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Cập nhật",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'tcontent'=>Html::errorSummary($model),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing KpiWorkRegisteredForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $model->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose'=>true,
                //'forceReload'=>'#crud-datatable-pjax',
                'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
            ];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
    }

     /**
     * Delete multiple existing KpiWorkRegisteredForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        $delOk = true;
        $fList = array();
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            try{
            	$model->delete();
            }catch(\Exception $e) {
            	$delOk = false;
            	$fList[] = $model->id;
            }
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose'=>true,
                //'forceReload'=>'#crud-datatable-pjax',
                'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
                'tcontent'=>$delOk==true?'Xóa thành công!':('Không thể xóa:'.implode('</br>', $fList)),
            ];           
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the KpiWorkRegisteredForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KpiWorkRegisteredForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KpiWorkRegisteredForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionHistoryDelete($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $model = KpiWorkRegisteredHistory::findOne($id);
        if (!$model) {
            return ['success' => false, 'message' => 'Không tìm thấy dữ liệu.'];
        }

        if($model->delete()){
            return [
                'success' => true, 
                'message' => 'Đã xóa lịch sử.'
            ];
            
        } else {
            return [
                'success' => false, 
                'message' => 'Xóa thất bại.'
            ];
        }
    }



}
