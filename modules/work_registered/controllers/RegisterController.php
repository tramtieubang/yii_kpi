<?php

namespace app\modules\work_registered\controllers;

use app\common\helpers\CommonSQL;
use app\common\helpers\DateHelper;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use app\models\KpiWorkRegisteredHistory;
use app\models\KpiWorkRegisteredStatus;
use app\models\KpiWorkReport;
use app\modules\staff\models\StaffForm;
use app\modules\work_assignment\models\KpiWorkAssignmentForm;
use Yii;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use yii\db\Expression;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

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
         $model = new StaffForm();

        return $this->render('register', [
            'model' => $model,
        ]);        
        //return $this->render('register');
    }

    public function actionEvents($start = null, $end = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // FullCalendar gửi dạng 2025-02-17T00:00:00Z
        if ($start) $start = date('Y-m-d H:i:s', strtotime($start));
        if ($end)   $end   = date('Y-m-d H:i:s', strtotime($end));
     
        // Giả sử role superadmin lưu trong Yii::$app->user->identity->role
        // Hoặc dùng RBAC: Yii::$app->user->can('superadmin')        
        $query = KpiWorkRegisteredForm::find()
            ->where(['>=', 'start_date', $start]);
            //->andWhere(['<=', 'end_date', $end]);
       if (!Yii::$app->user->isSuperadmin){
            $query->andWhere(['staff_id' => Yii::$app->user->id]);
        }
        $models = $query->all();

        //dd($models);

        $events = [];

        foreach ($models as $m) {
            $events[] = [
                'id'    => $m->id,
                'title' => $m->title,
                'start' => $m->start_date,
                'end'   => $m->end_date,
                'color' => $m->status ? $m->status->color : '#3788d8',
                'extendedProps' => [    // ✅ Thêm dữ liệu tùy chỉnh ở đây
                    'staff' => $m->staff ? $m->staff->name : 'Unknown',
                    'status' => $m->status ? $m->status->name : 'N/A',
                    'description' => $m->description,
                ],
            ];
        }

        return $events;
    }

    // chua su dung
    public function actionRegister()
    {
        $model = new StaffForm();

        //return $this->render('@app/modules/work_registered/views/default/register.php', [
        return $this->render('register', [
            'model' => $model,
        ]);       
    }
    
    public function actionCreate($start_str = null, $end_str = null)
    {
        $request = Yii::$app->request;
        $model = new KpiWorkRegisteredForm();

        // Nếu AJAX GET (ví dụ click vào lịch FullCalendar)
        if ($request->isAjax && $request->isGet) {
            // Gán ngày mặc định từ FullCalendar
            $model->start_date = $start_str ? DateHelper::formatVN($start_str) : null;
            $model->end_date   = $end_str ? DateHelper::formatVN($end_str) : null;

            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                'title' => "Đăng ký công việc",
                'content' => $this->renderAjax('create', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                            Html::button('Lưu lại', ['class'=>'btn btn-primary', 'id'=>'btn-create-register'])
            ];
        }

        // Nếu AJAX POST (submit form)
        if ($request->isAjax && $request->isPost) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            if ($model->load($request->post())) {
                // Convert ngày sang MySQL
                $model->start_date = DateHelper::toMySQL($model->start_date);
                $model->end_date   = DateHelper::toMySQL($model->end_date);

                $model->staff_id = Yii::$app->user->id ?? null;    
                //$model->status_id = 1; // Chưa duyệt
                $status = Yii::$app->request->post('status');
                $model->status_id = $status;
                $model->color = $model->status->color;
                //dd($status);

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save(false)) {
                        throw new \Exception("Không thể lưu công việc!");
                    }

                    // Lưu lịch sử                    
                    CommonSQL::saveRegisteredHistory(null, $model, 'create');
                         
                    // Gọi approve tự động nếu muốn
                    //$status = Yii::$app->request->post('status', 2);
                    if($status == 2) {
                        // Luu lich chinh thuc tu lich dang ky
                        CommonSQL::approve($model->id);
                        
                        //$modelAssignment = KpiWorkAssignmentForm::findOne($id);
                        $modelAssignment = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                        CommonSQL::saveAssignmentHistory(null, $modelAssignment, 'create');   
                    } 

                    $transaction->commit();
                    return [
                        'forceReload' => '#crud-datatable-pjax',
                        'title' => "Thêm mới thành công",
                        'content' => '<span class="text-success">Đã lưu dữ liệu!</span>',
                        'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
                    ];
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    Yii::error($e->getMessage(), __METHOD__);
                    return [
                        'title' => "Lỗi lưu dữ liệu",
                        'content' => '<span class="text-danger">Lỗi: ' . Html::encode($e->getMessage()) . '</span>',
                        'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
                    ];
                }
            }

            // Nếu validate thất bại
            return [
                'title' => "Lỗi dữ liệu",
                'content' => $this->renderAjax('create', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
            ];
        }

        // Nếu không phải AJAX (truy cập trực tiếp)
        if ($model->load($request->post())) {
            $model->start_date = DateHelper::toMySQL($model->start_date);
            $model->end_date   = DateHelper::toMySQL($model->end_date);
            $model->staff_id = Yii::$app->user->id ?? null;
  
            //$model->status_id = 1; // Chưa duyệt
            $status = Yii::$app->request->post('status');
            $model->status_id = $status;
            $model->color = $model->status->color;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    //$this->saveHistory(null, $model, 'create');
                    CommonSQL::saveAssignmentHistory(null, $model, 'create');
                         
                    // Gọi approve tự động nếu muốn
                    //$status = Yii::$app->request->post('status', 2);
                    if($status == 2) {
                        // Luu lich chinh thuc tu lich dang ky
                        CommonSQL::approve($model->id);
                        
                        //$modelAssignment = KpiWorkAssignmentForm::findOne($id);
                        $modelAssignment = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                        CommonSQL::saveAssignmentHistory(null, $modelAssignment, 'create');   
                    } 
                    
                    $transaction->commit();

                    Yii::$app->session->setFlash('success', 'Đã thêm mới công việc.');
                    return $this->redirect(['index']);
                } else {
                    throw new \Exception("Không thể lưu công việc!");
                }
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Lỗi: ' . $e->getMessage());
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = KpiWorkRegisteredForm::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Không tìm thấy bản ghi #$id");
        }

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // GET AJAX: hiện form update
        if ($request->isGet) {
            return [
                'title' => "Cập nhật công việc",
                'content' => $this->renderAjax('update', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default','data-bs-dismiss'=>"modal"]) .
                            Html::button('Cập nhật', ['class'=>'btn btn-primary', 'id'=>'btn-update-register'])
            ];
        }

        // POST AJAX: xử lý update
        if ($model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $status = (int)$request->post('status');

            try {
                // Lấy bản sao cũ để lưu lịch sử
                $oldModel = clone $model;

                // Gán status_id
                $model->status_id = $status;

                // Lấy màu từ bảng trạng thái hoặc mặc định
                $statusModel = KpiWorkRegisteredStatus::findOne($model->status_id);
                $model->color = $statusModel->color ?? '#3788d8';

                // Trường hợp: Chưa duyệt
                if ($status === 1) {
                    $model->save(false);
                    CommonSQL::saveRegisteredHistory($oldModel, $model, 'update');
                }

                // Trường hợp: Đã duyệt
                elseif ($status === 2) {
                    // Nếu đã có báo cáo → không cho sửa
                    if ($model->hasAnyReport()) {
                        return [
                            'success' => false,
                            'message' => 'Công việc đã có báo cáo, không thể sửa.'
                        ];
                    }

                    /* Cách 1: Duyệt lại */
                   /*  // Chưa có báo cáo → duyệt lại
                    $model->status_id = 1; // bật lại Chưa duyệt
                    $model->save(false);
                    CommonSQL::saveRegisteredHistory($oldModel, $model, 'update');

                    // Xóa phân công cũ nếu có
                    KpiWorkAssignment::deleteAll(['work_registered_id' => $model->id]);

                    // Tạo phân công mới từ lịch đăng ký
                    //CommonSQL::approve($model->id);
                    //$modelAssignment = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                    //CommonSQL::saveAssignmentHistory(null, $modelAssignment, 'update'); */

                    /* Cách 2: Giữ nguyên chỉ sửa nội dung không duyệt lại */
                    CommonSQL::saveRegisteredHistory($oldModel, $model, 'update');
                    // Luu công việc sửa
                    $model->save(false);

                    // Kiểm tra đã có phân công chưa
                    $assigned = KpiWorkAssignment::find()
                        ->where(['work_registered_id' => $model->id])
                        ->all();

                    // Nếu đã phân công 
                    if (!empty($assigned)) {
                        // Cập nhật các phân công hiện có
                        foreach ($assigned as $assignment) {
                            $assignment->title = $model->title;
                            $assignment->description = $model->description;
                            $assignment->start_date = $model->start_date;
                            $assignment->end_date = $model->end_date;
                            $assignment->color = $model->color; // đồng bộ màu
                            $assignment->save(false);

                            // Lưu lịch sử phân công
                            CommonSQL::saveAssignmentHistory(null, $assignment, 'update');
                        }
                    }
                    else { //chưa phân công
                        // Luu lich chinh thuc tu lich dang ky
                        CommonSQL::approve($model->id);                    
                        //$modelAssignment = KpiWorkAssignmentForm::findOne($id);
                        $modelAssignment = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                        CommonSQL::saveAssignmentHistory(null, $modelAssignment, 'update');
                    } 

                }

                // Trường hợp: Từ chối
                else {
                    $model->status_id = 1; // đặt lại Chưa duyệt
                    $model->save(false);
                    CommonSQL::saveRegisteredHistory($oldModel, $model, 'update');
                }

                $transaction->commit();

                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Cập nhật thành công",
                    'content' => '<span class="text-success">Đã cập nhật dữ liệu thành công!</span>',
                    'footer' => Html::button('Đóng', ['class' => 'btn btn-default', 'data-bs-dismiss' => "modal"])
                ];

            } catch (\Throwable $e) {
                $transaction->rollBack();
                return [
                    'title' => "Lỗi cập nhật",
                    'content' => '<span class="text-danger">Lỗi: ' . Html::encode($e->getMessage()) . '</span>',
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-secondary','data-bs-dismiss'=>"modal"])
                ];
            }
        }

        return [
            'title' => "Lỗi dữ liệu",
            'content' => $this->renderAjax('update', ['model' => $model]),
            'footer' => Html::button('Đóng', ['class'=>'btn btn-secondary','data-bs-dismiss'=>"modal"])
        ];
    }


}