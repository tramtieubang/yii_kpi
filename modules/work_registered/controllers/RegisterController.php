<?php

namespace app\modules\work_registered\controllers;

use app\common\helpers\DateHelper;
use app\modules\staff\models\StaffForm;
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

        // /dd($models);

        $events = [];

        foreach ($models as $m) {
            $events[] = [
                'id'    => $m->id,
                'title' => $m->title,
                'start' => $m->start_date,
                'end'   => $m->end_date,
                'color' => $m->status->color,//'#257e4a',  // tuỳ chỉnh
                'status' => $m->status->name,
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
                $model->status_id = 1; // Chưa duyệt

                //dd($model->status_id);

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save(false)) {
                        throw new \Exception("Không thể lưu công việc!");
                    }

                    // Lưu lịch sử
                    Yii::$app->db->createCommand()->insert('{{%kpi_work_registered_history}}', [
                        'work_registered_id' => $model->id,
                        'title' => $model->title,
                        'description' => $model->description,
                        'start_date' => $model->start_date,
                        'end_date' => $model->end_date,
                        'action_type' => 'create',
                        'updated_by' => Yii::$app->user->id ?? null,
                        'created_at' => new Expression('NOW()'),
                    ])->execute();

                    // Gọi approve tự động nếu muốn
                    $status = Yii::$app->request->post('status', 2);
                    if($status == 2) {
                        $this->actionApprove($model->id);
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
            $model->status_id = 1;

            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    Yii::$app->db->createCommand()->insert('{{%kpi_work_registered_history}}', [
                        'work_registered_id' => $model->id,
                        'title' => $model->title,
                        'description' => $model->description,
                        'start_date' => $model->start_date,
                        'end_date' => $model->end_date,
                        'action_type' => 'create',
                        'updated_by' => Yii::$app->user->id ?? null,
                        'created_at' => new Expression('NOW()'),
                    ])->execute();

                    $this->actionApprove($model->id);
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

        // Lần đầu load form
        if ($request->isGet) {
            return [
                'title' => "Cập nhật công việc",
                'content' => $this->renderAjax('update', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default','data-bs-dismiss'=>"modal"]) .
                            Html::button('Cập nhật', ['class'=>'btn btn-primary', 'id'=>'btn-update-register'])
            ];
        }

        // Xử lý submit AJAX (POST)
        if ($model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                // Lưu lịch sử trước khi cập nhật
                Yii::$app->db->createCommand()->insert('{{%kpi_work_registered_history}}', [
                    'work_registered_id' => $model->id,
                    'title' => $model->title,
                    'description' => $model->description,
                    'start_date' => $model->start_date,
                    'end_date' => $model->end_date,
                    'action_type' => 'update',
                    'updated_by' => Yii::$app->user->id ?? null,
                    'created_at' => new \yii\db\Expression('NOW()'),
                ])->execute();

                //dd( $model);

                $model->save(false);
                $transaction->commit();

                return [
                    'forceReload' => '#crud-datatable-pjax',
                    'title' => "Cập nhật thành công",
                    'content' => '<span class="text-success">Đã lưu dữ liệu!</span>',
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-default','data-bs-dismiss'=>"modal"])
                ];
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(), __METHOD__);
                return [
                    'title' => "Lỗi cập nhật",
                    'content' => '<span class="text-danger">Lỗi: ' . Html::encode($e->getMessage()) . '</span>',
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-default','data-bs-dismiss'=>"modal"])
                ];
            }
        }

        // Nếu load POST thất bại
        return [
            'title' => "Lỗi dữ liệu",
            'content' => $this->renderAjax('update', ['model' => $model]),
            'footer' => Html::button('Đóng', ['class'=>'btn btn-default','data-bs-dismiss'=>"modal"])
        ];
    }


    public function actionApprove($id)
    {
        $iStatus = 0;

        $registration = KpiWorkRegisteredForm::findOne($id);

        if (!$registration) {
            throw new NotFoundHttpException("Không tìm thấy công việc đăng ký này.");
        }

        /* // Chỉ cho lãnh đạo duyệt
        if (!Yii::$app->user->can('kpi.approve')) {
            throw new ForbiddenHttpException("Bạn không có quyền duyệt công việc.");
        } */

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // 1️⃣ Cập nhật trạng thái công việc đăng ký
            $registration->status_id = 2; // 2 = Duyệt
            $registration->updated_at = new \yii\db\Expression('NOW()');
            $registration->save(false);

            // 2️⃣ Gán KPI vào bảng KPI thực tế (kpi_work_assignment)
            Yii::$app->db->createCommand()->insert('{{%kpi_work_assignment}}', [
                'work_registered_id' => $registration->id,
                'staff_id' => $registration->staff_id,
                'status_id' => 4, // 4 = Đang thực hiện (theo bảng status)
                'title' => $registration->title,
                'start_date' => $registration->start_date,
                'end_date' => $registration->end_date,
                'color' => '#3788d8', // fallback màu xanh dương
                'assigned_at' => new \yii\db\Expression('NOW()'),
            ])->execute();

            $transaction->commit();

            $iStatus = 1;
            Yii::$app->session->setFlash('success', 'Duyệt công việc thành công, phân công và lưu lịch vào calendar.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Duyệt công việc thất bại: ' . $e->getMessage());
        }

        //return $this->redirect(['index']);
        return  $iStatus;
    }






}