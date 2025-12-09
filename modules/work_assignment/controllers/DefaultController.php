<?php

namespace app\modules\work_assignment\controllers;

use app\common\helpers\CommonSQL;
use app\models\KpiWorkRegistered;
use app\models\KpiWorkRegisteredHistory;
use app\modules\work_assignment\models\KpiWorkAssignmentForm;
use app\modules\work_assignment\models\KpiWorkAssignmentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;
use Yii;

/**
 * DefaultController implements the CRUD actions for KpiWorkAssignmentForm model.
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
    				],
    			],
		];
	}

    /**
     * Lists all KpiWorkAssignmentForm models.
     * @return mixed
     */
    public function actionIndex()
    {    
        // Cap nhat tre han
        $this->actionUpdateOverdue();

        //dd(date('Y-m-d H:i:s'));

        $searchModel = new KpiWorkAssignmentSearch();
  		if(isset($_POST['search']) && $_POST['search'] != null){
            $dataProvider = $searchModel->search(Yii::$app->request->post(), $_POST['search']);
        } else if ($searchModel->load(Yii::$app->request->post())) {
            $searchModel = new KpiWorkAssignmentSearch(); // "reset"
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
     * Displays a single KpiWorkAssignmentForm model.
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
                            Html::a('Sửa',['update', 'id'=>$id, 'fromView'=>1],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    public function actionFilter()
    {
        $searchModel = new KpiWorkAssignmentSearch();

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
     * Creates a new KpiWorkAssignmentForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($staff_id = null)
    {
        $request = Yii::$app->request;
        $model = new KpiWorkAssignmentForm();  

        // Nếu tạo mới thì gán staff_id từ tham số
        if ($staff_id !== null) {
            $model->staff_id = $staff_id;
        }


        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            
            // ------------ HIỂN THỊ FORM TẠO -------------
            if ($request->isGet) {
                return [
                    'title' => 'Phân công việc',
                    'content' => $this->renderAjax('create', [
                        'model' => $model,
                        'isUpdate' => false,
                    ]),
                    'footer' =>
                        Html::button('Đóng', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => 'modal']) .
                        Html::button('Lưu lại', ['class' => 'btn btn-primary', 'type' => 'submit'])
                ];
            }
            // ------------- LƯU DỮ LIỆU ---------------
            else if($model->load($request->post()) && $model->save()){

                // lấy bản ghi đã lưu chính xác
                 $saved = KpiWorkAssignmentForm::findOne(['id' => $model->id]);

                // Lưu lịch sử
                CommonSQL::saveAssignmentHistory(null, $saved, 'create');
    
                // Đếm lại số dòng grid con
                $searchModel = new KpiWorkAssignmentSearch();
                $searchModel->staff_id = $staff_id;
                $dataProvider = $searchModel->searchChild(Yii::$app->request->queryParams);
                $count = $dataProvider->getTotalCount();

                return [
                    //'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                    'forceReload'=>'#pjax-jobs-grid-'.$staff_id,
                    'newCount' => $count,
                    'staff_id'  => $staff_id,
                    'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
                    'forceClose' => true,
                    'tcontent'=>'Phân công việc thành công!',
                ];
         
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
     * Updates an existing KpiWorkAssignmentForm model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        $fromView = $request->get('fromView', 0);

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
            	if ($fromView == 1) {
                    // ⭐ Trường hợp vào từ form VIEW                     
                    return [
                        'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,  
                        'system_id'   => $model->staff_id,              // ⭐ ID để mở lại expand row
                        'title'       => "Cập nhật",
                        'content'     => $this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'tcontent'    => 'Cập nhật thành công!',
                        'footer'      => Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                                        Html::a('Sửa',['update','id'=>$id, 'fromView'=>1],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];  
                }else{
                	return [
                       
                        'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                        'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                        'system_id' => $model->staff_id,    // ✔ Gửi ID về để mở lại expand row
                        'forceClose' => true,
                        'tcontent'=>'Cập nhật công việc phân công việc thành công!',
                    ];                    
                }
            }else{
                 return [
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
     * Delete an existing KpiWorkAssignmentForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;

        // ==============================
        // 1. Lấy model phân công (ActiveRecord)
        // ==============================
        $model = $this->findModel($id); // KpiWorkAssignment
        $staff_id = $model->staff_id;
        $registeredId = $model->work_registered_id; // có thể null nếu phân công độc lập

        // ==============================
        // 2. Lưu snapshot phân công trước khi xóa (dùng để ghi lịch sử)
        // ==============================
        $oldRegistered = KpiWorkRegistered::findOne($registeredId);

        // ==============================
        // 3. XÓA PHÂN CÔNG
        // ==============================
        $model->delete();

        // ==============================
        // 4. Nếu phân công liên kết với đăng ký -> khôi phục trạng thái + lưu lịch sử
        // ==============================
        if ($registeredId) {

            // 4a. Lấy model đăng ký
            $registered = KpiWorkRegistered::findOne($registeredId);

            if ($registered) {
                // 4b. Khôi phục trạng thái đăng ký từ lịch sử
                $newregistered = $this->restoreRegisteredStatus($registeredId);

                // 4c. Ghi lịch sử đăng ký với action_type = 'unassign'
                CommonSQL::saveRegisteredHistory($oldRegistered, $newregistered, 'unassign');
            } else {
                // Không tìm thấy đăng ký, chỉ log warning
                Yii::warning("Phân công #{$id} liên kết với đăng ký #{$registeredId} nhưng không tìm thấy bản ghi đăng ký.");
            }
        }

        // ==============================
        // 5. Trả kết quả về giao diện
        // ==============================
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            $searchModel = new KpiWorkAssignmentSearch();
            $searchModel->staff_id = $staff_id;
            $count = $searchModel->searchChild(Yii::$app->request->queryParams)->getTotalCount();

            return [
                'forceClose' => true,
                'forceReload' => '#pjax-jobs-grid-' . $staff_id,
                'newCount' => $count,
                'staff_id' => $staff_id,
                'tcontent' => 'Hủy phân công việc thành công!',
            ];
        }

        return $this->redirect(['index']);
    }

    /**
     * Khôi phục trạng thái đăng ký công việc dựa trên lịch sử gần nhất trước khi phân công
     *
     * @param int $registeredId ID của công việc đăng ký
     * @return KpiWorkRegistered|null Trả về model đăng ký sau khi restore, hoặc null nếu không tìm thấy
    */
    protected function restoreRegisteredStatus($registeredId)
    {
        // 1. Tìm bản ghi lịch sử gần nhất trước khi trạng thái được phân công (assigned)
        $lastHistory = KpiWorkRegisteredHistory::find()
            ->where(['work_registered_id' => $registeredId])
            ->andWhere(['action_type' => 'assign']) // hoặc action_type phù hợp nếu bạn dùng assign/unassign
            ->orderBy(['id' => SORT_DESC])
            ->one();

        // 2. Nếu có lịch sử, khôi phục trạng thái cũ
        if ($lastHistory) {
            $registered = KpiWorkRegistered::findOne($registeredId);
            if ($registered) {
                $registered->status_id = $lastHistory->old_status;
                $registered->save(false);

                return $registered;
            }
        }

        // 3. Nếu không có lịch sử hoặc không tìm thấy bản ghi đăng ký
        Yii::warning("Không thể restore trạng thái cho đăng ký #{$registeredId}. Lịch sử phân công không tìm thấy.");
        return null;
    }

     /**
     * Delete multiple existing KpiWorkAssignmentForm model.
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
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax',
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
     * Finds the KpiWorkAssignmentForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return KpiWorkAssignmentForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = KpiWorkAssignmentForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Cập nhật trạng thái công việc trễ hạn
    */
    public function actionUpdateOverdue()
    {
     
        // khong duyet for
        $now = date('Y-m-d H:i:s');

        $count = KpiWorkAssignmentForm::updateAll(
            ['status_id' => 3],
            [
                'and',
                ['or',
                    ['status_id' => 1],
                    ['status_id' => 4]
                ],
                new \yii\db\Expression("DATE(end_date) < :now", ['now' => $now])
            ]
        );

        return $count;

       // echo $count . " công việc được đánh dấu trễ hạn.";

        /* 
        $overdueJobs = KpiWorkAssignmentForm::find()
            ->where(['status_id' => 1]) // 1 = Chưa hoàn thành
            ->andWhere(['<', 'end_date', $now])
            ->all();

        foreach ($overdueJobs as $job) {
            $job->status_id = 3; // 3 = Trễ hạn
            $job->save(false); // không validate
        } */
        //echo count($overdueJobs) . " công việc được đánh dấu trễ hạn.\n";
    }

}
