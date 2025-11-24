<?php

namespace app\modules\work_registered\controllers;

use app\common\helpers\DateHelper;
use app\modules\employees\models\EmployeesForm;
use Yii;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use yii\db\Expression;
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

    public function actionEvents($start = null, $end = null)
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // FullCalendar gửi dạng 2025-02-17T00:00:00Z
        if ($start) $start = date('Y-m-d H:i:s', strtotime($start));
        if ($end)   $end   = date('Y-m-d H:i:s', strtotime($end));

        $models = KpiWorkRegisteredForm::find()
            ->where(['>=', 'date_start', $start])
            //->andWhere(['>=', 'date_end', $end])
            //->where(['>=', new Expression('DATE(date_start)'), $start])
            //->andwhere(['>=', new Expression('DATE(date_end)'), $end])
            ->all();

        $events = [];

        foreach ($models as $m) {
            $events[] = [
                'id'    => $m->id,
                'title' => $m->title,
                'start' => $m->date_start,
                'end'   => $m->date_end,
                'color' => '#257e4a',  // tuỳ chỉnh
            ];
        }

        return $events;
    }

    // chua su dung
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
        $model->date_start = DateHelper::formatVN($start_str);
        $model->date_end   = DateHelper::formatVN($end_str);

        // Nếu request AJAX
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;

            // Hiển thị form lần đầu (AJAX GET)
            if ($request->isGet) {
                return [
                    'title' => "Đăng ký công việc",
                    'content' => $this->renderAjax('create', ['model' => $model]),
                    'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"]) .
                                Html::button('Lưu lại', ['class'=>'btn btn-primary','type'=>"submit"])
                ];
            }

            // Xử lý submit form (AJAX POST)
            if ($model->load($request->post())) {
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // --- Lưu công việc mới ---
                    if ($model->save(false)) { // save(false) nếu đã validate phía client
                        // --- Lưu lịch sử ---
                        Yii::$app->db->createCommand()->insert('{{%kpi_work_registered_history}}', [
                            'work_registered_id' => $model->id,
                            'title' => $model->title,
                            'description' => $model->description,
                            'date_start' => $model->date_start,
                            'date_end' => $model->date_end,
                            'action_type' => 'create',
                            'updated_by' => Yii::$app->user->id ?? null,
                            'created_at' => new \yii\db\Expression('NOW()'),
                        ])->execute();

                        $transaction->commit();
                        return [
                            'forceReload' => '#crud-datatable-pjax',
                            'title' => "Thêm mới thành công",
                            'content' => '<span class="text-success">Đã lưu dữ liệu!</span>',
                            'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
                        ];
                    } else {
                        throw new \Exception("Không thể lưu công việc!");
                    }

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

            // Nếu load POST nhưng validate thất bại
            return [
                'title' => "Lỗi dữ liệu",
                'content' => $this->renderAjax('create', ['model' => $model]),
                'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left','data-bs-dismiss'=>"modal"])
            ];
        }

        // Nếu không phải AJAX
        if ($model->load($request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->save(false)) {
                    // Lưu lịch sử
                    Yii::$app->db->createCommand()->insert('{{%kpi_work_registered_history}}', [
                        'work_id' => $model->id,
                        'title' => $model->title,
                        'description' => $model->description,
                        'date_start' => $model->date_start,
                        'date_end' => $model->date_end,
                        'action_type' => 'create',
                        'updated_by' => Yii::$app->user->id ?? null,
                        'created_at' => new \yii\db\Expression('NOW()'),
                    ])->execute();

                    $transaction->commit();
                    return $this->redirect(['index']);
                } else {
                    throw new \Exception("Không thể lưu công việc!");
                }
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::error($e->getMessage(), __METHOD__);
                Yii::$app->session->setFlash('error', 'Thêm mới thất bại: ' . $e->getMessage());
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
                    'date_start' => $model->date_start,
                    'date_end' => $model->date_end,
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




}