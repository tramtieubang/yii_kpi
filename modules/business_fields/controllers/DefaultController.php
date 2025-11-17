<?php

namespace app\modules\business_fields\controllers;

use Yii;
use app\modules\business_fields\models\BusinessFieldsForm;
use app\modules\business_fields\models\BusinessFieldsSearch;
use app\modules\employees\models\EmployeesForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for BusinessFieldsForm model.
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
     * Lists all BusinessFieldsForm models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new BusinessFieldsSearch();
  		if(isset($_POST['search']) && $_POST['search'] != null){
            $dataProvider = $searchModel->search(Yii::$app->request->post(), $_POST['search']);
        } else if ($searchModel->load(Yii::$app->request->post())) {
            $searchModel = new BusinessFieldsSearch(); // "reset"
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
     * Displays a single BusinessFieldsForm model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "BusinessFieldsForm",
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
     * Creates a new BusinessFieldsForm model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new BusinessFieldsForm();  

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
            
            // --- Lưu thành công ---
            if($model->load($request->post()) && $model->save()){

                 // ⚡ Reset form (model mới)
                $model = new BusinessFieldsForm();

                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Thêm mới",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'tcontent'=>'Thêm mới thành công!',
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                            Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            } 
            
            // --- Lỗi validate ---
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
            return $this->redirect(['view', 'id' => $model->id]);
        } 

        return $this->render('create', [
            'model' => $model,
        ]);
                       
    }


    /**
     * Updates an existing BusinessFieldsForm model.
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

            // Hiển thị form lần đầu
            if($request->isGet){
                return [
                    'title'=> "Cập nhật",
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::button('Lưu lại',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }
            
             // Lưu thành công → Không đóng form, giữ nguyên form đang mở
            if($model->load($request->post()) && $model->save()){
            	if(Yii::$app->params['showView']){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Cập nhật",
                         // render lại chính form update với dữ liệu mới vừa lưu
                        'content'=>$this->renderAjax('view', [
                            'model' => $model,
                        ]),
                        'tcontent'=>'Cập nhật thành công!',
                        'footer'=> Html::button('Đóng lại',['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]).
                                Html::a('Sửa',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                    ];    
                }else{
                	
                    return [
                        //'forceClose'=>true,
                        'forceReload' => '#crud-datatable-pjax',
                        'title'       => "Cập nhật",
                        // render lại chính form update với dữ liệu mới vừa lưu
                        'content'     => $this->renderAjax('update', ['model' => $model]),
                        'tcontent'    => 'Cập nhật thành công!',
                        'footer'      =>
                            Html::button('Đóng lại', ['class' => 'btn btn-default pull-left', 'data-bs-dismiss' => "modal"]) .
                            Html::button('Lưu lại', ['class' => 'btn btn-primary', 'type' => "submit"])
                    ];
                }
            }

            // Nếu validate lỗi
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

        /*
        *   Process for non-ajax request
        */
        // Không phải ajax
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }       
    }

    /**
     * Delete an existing BusinessFieldsForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);

        $item = EmployeesForm::find()
            ->select('business_field_id')
            ->where(['business_field_id' => $id])
            ->exists(); // dùng exists() nhanh hơn, trả true/false

        // Nếu có nhân viên thuộc Lĩnh vực kinh doanh → không cho xóa
        if ($item) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'tcontent' => 'Lĩnh vực kinh doanh đã có nhân viên, bạn không thể xóa!'
            ];
        }

        // Nếu không có nhân viên → xóa
        $model->delete();

        // AJAX request
        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [
                'forceClose' => true,
                'forceReload' => '#crud-datatable-pjax'
            ];
        }

        // Non-Ajax → redirect
        return $this->redirect(['index']);
    }

     /**
     * Delete multiple existing BusinessFieldsForm model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkdelete()
    {        
        $request = Yii::$app->request;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $pks = explode(',', $request->post('pks')); 
        $failedList = [];

        foreach ($pks as $pk) {
            $model = $this->findModel($pk);

            // Kiểm tra Vị trí kinh doanh có nhân viên hay không
            $hasEmployee = EmployeesForm::find()
                ->where(['business_field_id' => $pk])
                ->exists();   // Tốt hơn all()

            if ($hasEmployee) {
                $failedList[] = $model->name;
                continue;
            }

            try {
                $model->delete();
            } catch (\Exception $e) {
                $failedList[] = $model->name;
            }
        }

        // Trường hợp xóa thành công toàn bộ
        if (empty($failedList)) {
            return [
                'forceClose'  => true,
                'forceReload' => '#crud-datatable-pjax',
                'tcontent'    => 'Xóa thành công!',
            ];
        }

        // Trường hợp có lỗi
        return [
            'title'   => 'Thông báo',
            'content' => 
                '<div class="alert alert-danger">
                    Không thể xóa các lĩnh vực kinh doanh: <b>' . implode(', ', $failedList) . '</b><br>
                    Do đã có nhân viên.
                </div>
                <script>
                    setTimeout(function(){
                        $(\'#ajaxCrudModal\').modal(\'hide\');
                    }, 5000);
                </script>',
            'forceClose'  => false,   // Giữ lại modal để người dùng thấy thông báo
            'forceReload' => '#crud-datatable-pjax',
        ];
    }

    /**
     * Finds the BusinessFieldsForm model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BusinessFieldsForm the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BusinessFieldsForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
