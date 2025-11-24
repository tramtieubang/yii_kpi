<?php

namespace app\modules\staff\controllers;

use app\common\helpers\DateHelper;
use Yii;

use app\modules\staff\models\StaffSearch;
use app\modules\staff\models\StaffForm;
use app\modules\user_management\user\models\UserForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * DefaultController implements the CRUD actions for StaffForm model.
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
    				'class' => VerbFilter::className(),
    				'actions' => [
    					'delete' => ['POST'],
    				],
    			],
		];
	}

    /**
     * Lists all StaffForm models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new StaffSearch();
  		if(isset($_POST['search']) && $_POST['search'] != null){
            $dataProvider = $searchModel->search(Yii::$app->request->post(), $_POST['search']);
        } else if ($searchModel->load(Yii::$app->request->post())) {
            $searchModel = new StaffSearch(); // "reset"
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
     * Displays a single StaffForm model.
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

    /**
     * Creates a new StaffForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new StaffForm();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;

            // --- Hiển thị form lần đầu ---
            if($request->isGet){
                return [
                    'title'=> "Thêm mới",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
            
            if($model->load($request->post())) {
                
                // --- Lưu thành công ---
                $model->hire_date = DateHelper::formatDateVNToMySQL($model->hire_date);
                if($model->save()) {

                    // --- Nếu có username (muốn tạo hoặc gán user) ---
                    if (!empty($model->username)) {

                        // Tìm user theo username
                        $existingUser = UserForm::find()->where(['username' => $model->username])->one();

                        if ($existingUser) {

                            // --- TH1: Username tồn tại nhưng không nhập password ---
                            if (empty($model->password)) {
                                return [
                                    'title' => 'Lỗi',
                                    'tcontent' => 'Tài khoản đã tồn tại, vui lòng nhập mật khẩu để liên kết hoặc đổi username!',
                                ];
                            }

                            // --- TH2: Username tồn tại -> kiểm tra mật khẩu có đúng không ---
                            if (!$existingUser->validatePassword($model->password)) {
                                return [
                                    'title' => 'Lỗi',
                                    'tcontent' => 'Mật khẩu không đúng với tài khoản đã tồn tại!',
                                ];
                            }

                            // --- Mật khẩu đúng -> Gán user_id cho employees ---
                            $model->staff_id = $existingUser->id;
                            $model->save(false);

                        } else {

                            // --- TH3: Username chưa tồn tại -> tạo user mới ---
                            if (empty($model->password)) {
                                return [
                                    'title' => 'Lỗi',
                                    'tcontent' => 'Bạn phải nhập mật khẩu để tạo tài khoản mới!',
                                ];
                            }

                            $user = new UserForm();

                            $user->username = $model->username;
                            $user->email = $model->email ?? $model->username . '@example.com';
                            $user->status = $model->status;
                            $user->setPassword($model->password);
                            $user->generateAuthKey();
                            
                            //$user->save(false);
                            // Gán user_id mới tạo
                            //$model->user_id = $user->id;
                            //$model->save(false);
                            // Lưu user trước
                            if ($user->save(false)) {
                                // Lấy ID tự sinh của user
                                $model->staff_id = $user->id;

                                // Lưu staff sau khi đã có staff_id
                                $model->save(false);
                            }
                        }
                    }

                    // --- Reset form ---
                    $model = new StaffForm();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Thêm mới",
                        'content' => $this->renderAjax('create', ['model' => $model]),
                        'tcontent' => 'Thêm mới thành công!',
                        'footer' =>
                            Html::button('Đóng lại', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"]) .
                            Html::button('Lưu lại', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }  

            // --- Lỗi validate ---
            $model->hire_date = DateHelper::formatDateMySQLToVN($model->hire_date);
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
           
        /*
        *   Process for non-ajax request
        */
        // --- Trường hợp không dùng ajax ---
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->staff_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
        
    }

    /**
     * Updates an existing EmployeesForm model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        // Load dữ liệu user liên kết (nếu có)
        if ($model->user) {
            $model->username = $model->user->username;
            $model->status = $model->user->status;
        }

        // Chuyển hire_date MySQL -> VN để hiển thị form
        $model->hire_date = DateHelper::formatDateMySQLToVN($model->hire_date);

        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if ($request->isGet) {
                return [
                    'title'=> "Cập nhật",
                    'content'=>$this->renderAjax('update', ['model' => $model]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                            Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }

            if ($model->load($request->post())) {
                // Xử lý user
                $username = trim($model->username);
                $password = $model->password;

                if (!empty($username)) {
                    $user = UserForm::findOne($model->staff_id); // dùng staff_id làm ID user

                    if ($user) {
                        // Update user hiện có
                        $user->username = $username;
                        $user->status = $model->status;
                        if (!empty($password)) {
                            $user->setPassword($password);
                        }
                        $user->save(false);
                    } else {
                        // Tạo user mới
                        $user = new UserForm();
                        $user->id = $model->staff_id;
                        $user->username = $username;
                        $user->status = $model->status;
                        $user->email = $model->email ?? $username . '@example.com';
                        if (!empty($password)) {
                            $user->setPassword($password);
                        }
                        $user->generateAuthKey();
                        $user->save(false);
                    }
                }

                // Xử lý hire_date
                $model->hire_date = DateHelper::formatDateVNToMySQL($model->hire_date);

                if ($model->save()) {
                    $model->hire_date = DateHelper::formatDateMySQLToVN($model->hire_date);
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Cập nhật",
                        'content'=>$this->renderAjax('update', ['model' => $model]),
                        'tcontent'=>'Cập nhật thành công!',
                        'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                    ];
                } else {
                    $model->hire_date = DateHelper::formatDateMySQLToVN($model->hire_date);
                    return [
                        'title'=> "Cập nhật",
                        'content'=>$this->renderAjax('update', ['model' => $model]),
                        'tcontent'=>Html::errorSummary($model),
                        'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                    ];
                }
            }

            // Nếu lỗi validate Ajax
            return [
                'title'=> "Cập nhật",
                'content'=>$this->renderAjax('update', ['model' => $model]),
                'tcontent'=>Html::errorSummary($model),
                'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                        Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
            ];
        }

        // Non-Ajax
        if ($model->load($request->post())) {
            $username = trim($model->username);
            $password = $model->password;

            if (!empty($username)) {
                $user = UserForm::findOne($model->staff_id); // dùng staff_id làm ID user

                if ($user) {
                    $user->username = $username;
                    $user->status = $model->status;
                    if (!empty($password)) {
                        $user->setPassword($password);
                    }
                    $user->save(false);
                } else {
                    $user = new UserForm();
                    $user->id = $model->staff_id;
                    $user->username = $username;
                    $user->status = $model->status;
                    $user->email = $model->email ?? $username . '@example.com';
                    if (!empty($password)) {
                        $user->setPassword($password);
                    }
                    $user->generateAuthKey();
                    $user->save(false);
                }
            }

            $model->hire_date = DateHelper::formatDateVNToMySQL($model->hire_date);

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->staff_id]);
            } else {
                $model->hire_date = DateHelper::formatDateMySQLToVN($model->hire_date);
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Delete an existing EmployeesForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        // Kiem tra da phat sinh nghiep vu cac table 
        // kpi_work_registered, kpi_work_assignment, kpi_kpi_evaluation, kpi_summary
        // Kiểm tra ràng buộc
        $exists = (new \yii\db\Query())
            ->from('kpi_work_registered')
            ->where(['staff_id' => $id])
            ->exists()
            || (new \yii\db\Query())
            ->from('kpi_work_assignment')
            ->where(['staff_id' => $id])
            ->exists()
            || (new \yii\db\Query())
            ->from('kpi_kpi_evaluation')
            ->where(['staff_id' => $id])
            ->exists()
            || (new \yii\db\Query())
            ->from('kpi_summary')
            ->where(['staff_id' => $id])
            ->exists();

        if ($exists) {
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceClose' => true,
                    'forceReload' => false,
                    'tcontent' => 'Nhân viên này đã phát sinh nghiệp vụ KPI, không thể xóa!'
                ];
            } else {
                 return [
                    'forceClose' => true,
                    'forceReload' => false,
                    'tcontent' => 'Nhân viên này đã phát sinh nghiệp vụ KPI, không thể xóa!'
                ];
            }
        }
       
        $model->delete();

        // Nếu nhân viên liên kết user thì xóa luôn
        if ($model->staff_id) {
            $user = UserForm::findOne($model->staff_id);
            if ($user) {
                try {
                    $user->delete();
                } catch (\Exception $e) {
                    Yii::error("Không thể xóa user liên kết với nhân viên #$id: " . $e->getMessage());
                }
            }
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing EmployeesForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */

    public function actionBulkdelete()
    {
        $request = Yii::$app->request;
        
        // Lấy danh sách PK, nếu null trả về mảng rỗng
        $pksPost = $request->post('pks');
        $pks = $pksPost !== null ? explode(',', $pksPost) : [];
        //dd($pks);

        if(empty($pks)) {
            $message = 'Vui lòng chọn bản ghi để xóa!';
            if ($request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return [
                    'forceClose' => true,
                    'forceReload' => false,
                    'tcontent' => $message,
                ];
            } else {
                 return [
                    'forceClose' => true,
                    'forceReload' => false,
                    'tcontent' => $message,
                ];
            }
        }

        $delOk = true;
        $fList = [];

        // Bảng cần kiểm tra KPI
        $tables = [
            'kpi_work_registered' => 'staff_id',
            'kpi_work_assignment' => 'staff_id',
            'kpi_kpi_evaluation' => 'staff_id',
            'kpi_summary' => 'staff_id',
        ];

        foreach ($pks as $pk) {
            $model = $this->findModel($pk);

            // Kiểm tra ràng buộc trong các bảng KPI
            $exists = false;
            foreach ($tables as $table => $column) {
                $exists = (new \yii\db\Query())
                    ->from($table)
                    ->where([$column => $pk])
                    ->exists();
                if ($exists) break;
            }

            if ($exists) {
                $delOk = false;
                $fList[] = $model->id;
                continue;
            }

            // Xóa nếu không có ràng buộc
            try {
                
                $userId = $model->staff_id;  // Lưu lại user_id trước khi xóa nhân viên
                $model->delete();

                // Nếu có user liên kết, xóa luôn
                if ($userId) {
                    $user = UserForm::findOne($userId);
                    if ($user) {
                        try {
                            $user->delete();
                        } catch (\Exception $e) {
                            Yii::error("Không thể xóa user liên kết với nhân viên #$pk: " . $e->getMessage());
                        }
                    }
                }
                
            } catch (\Exception $e) {
                $delOk = false;
                $fList[] = $model->id;
            }
        }

        // Trả kết quả
        $message = $delOk
            ? 'Xóa thành công!'
            : 'Không thể xóa (đã phát sinh KPI hoặc lỗi): ' . implode(', ', $fList);

        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax',
                'tcontent' => $message,
            ];
        } else {
            Yii::$app->session->setFlash($delOk ? 'success' : 'error', $message);
            return $this->redirect(['index']);
        }
    }

    /**
     * Finds the StaffForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaffForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaffForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
