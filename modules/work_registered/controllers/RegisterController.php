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
class RegisterController extends Controller
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
         $model = new EmployeesForm();

        return $this->render('register', [
            'model' => $model,
        ]);        
        //return $this->render('register');
    }

    public function actionRegister()
    {
        $model = new EmployeesForm();

        //return $this->render('@app/modules/work_registered/views/default/register.php', [
        return $this->render('register', [
            'model' => $model,
        ]);       
    }

    
   public function actionCreate($start_str = null, $end_str = null)
    {
        $request = Yii::$app->request;
        $model = new KpiWorkRegisteredForm();

        // Gán ngày giờ mặc định từ FullCalendar
        $model->date_start = $start_str;
        $model->date_end = $end_str;

        // Nếu request AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Hiển thị form lần đầu (AJAX GET)
            if ($request->isGet) {
                return [
                    'title' => "Đăng ký công việc",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                                Html::button('Lưu', ['class'=>'btn btn-primary','type'=>"submit"])
                ];
 
            }

            // Xử lý submit form (AJAX POST)
            if ($model->load($request->post()) && $model->save()) {
                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Thêm mới thành công",
                    'content' => '<span class="text-success">Đã lưu dữ liệu!</span>',
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
                ];
            }

            // Lỗi validate
            return [
                'title' => "Lỗi dữ liệu",
                'content' => $this->renderAjax('create', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
            ];
        }

        // Nếu không phải AJAX
        if ($model->load($request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }



}