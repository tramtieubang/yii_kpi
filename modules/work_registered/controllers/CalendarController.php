<?php

namespace app\modules\work_registered\controllers;

use app\models\KpiWorkAssignment;
use app\models\KpiWorkReport;
use app\modules\staff\models\StaffForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * RegisterController implements the Controller.
 */
class CalendarController extends Controller
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
         $model = StaffForm::find()
        ->select(['staff_id', 'name'])
        ->indexBy('staff_id')
        ->column();

        //return $this->render('@app/modules/work_registered/views/default/calendar.php', [
        return $this->render('calendar', [
            'model' => $model
        ]);        
        //return $this->render('register');
    }

    public function actionCalendar()
    {
        return $this->render('calendar');
    }

    public function actionEvents($start = null, $end = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // FullCalendar gửi dạng 2025-02-17T00:00:00Z
        if ($start) $start = date('Y-m-d H:i:s', strtotime($start));
        if ($end)   $end   = date('Y-m-d H:i:s', strtotime($end));
     
        // Giả sử role superadmin lưu trong Yii::$app->user->identity->role
        // Hoặc dùng RBAC: Yii::$app->user->can('superadmin')        
        $query = KpiWorkAssignment::find()
            ->where(['>=', 'start_date', $start]);
            //->andWhere(['<=', 'end_date', $end]);
       if (!Yii::$app->user->isSuperadmin){
            $query->andWhere(['staff_id' => Yii::$app->user->id]);
        }
        $models = $query->all();    

        $events = [];

        foreach ($models as $m) {
            $events[] = [
                'id'    => $m->id,
                'title' => $m->title,
                'start' => $m->start_date,
                'end'   => $m->end_date,
                'color' => $m->status->color, //'#257e4a',  // tuỳ chỉnh
                //'status' => $m->status->name,
                'extendedProps' => [    // ✅ Thêm dữ liệu tùy chỉnh ở đây
                    'staff' => $m->staff ? $m->staff->name : 'Unknown',
                    'status' => $m->status ? $m->status->name : 'N/A',
                    'description' => $m->description,
                ],
            ];
        }

        return $events;
    }

   public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        // 🔹 Query assignment theo ID
        $query = KpiWorkAssignment::find()->where(['id' => $id]);

        // 🔹 Lấy 1 bản ghi duy nhất (ActiveRecord object)
        $model = $query->one();

        // 🔹 Kiểm tra tồn tại
        if (!$model) {
            throw new NotFoundHttpException("Không tìm thấy phân công có ID: $id");
        }

        $report = KpiWorkReport::find()
            ->where(['work_assignment_id' => $id])
            ->one();

        if (!$report) {
            $report = new KpiWorkReport();
            $report->work_assignment_id = $id; // gán sẵn nếu cần
        }
        // 🔹 Trả dữ liệu JSON cho modal
        return [
            'title' => "Chi tiết lịch",
            'content' => $this->renderAjax('view', [
                'model' => $model,
                'report' => $report,
            ]),
            'footer' => Html::button('Đóng', ['class'=>'btn btn-default pull-left', 'data-bs-dismiss'=>"modal"]) .
                        Html::button('Gửi báo cáo', ['class'=>'btn btn-success', 'id'=>'btn-report-assignment'])
        ];
    }

    public function actionReport($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $model = KpiWorkAssignment::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('Không tìm thấy phân công.');
        }

        if (Yii::$app->request->isPost) {
            
            $post = Yii::$app->request->post();

            // Cập nhật trạng thái
            if (!empty($post['KpiWorkAssignment']['status_id'])) {
                $model->status_id = $post['KpiWorkAssignment']['status_id'];
            }

            // 🔍 Kiểm tra báo cáo cũ
            $report = KpiWorkReport::find()
                ->where(['work_assignment_id' => $model->id])
                ->one();

            // Nếu chưa có → tạo mới
            if (!$report) {
                $report = new KpiWorkReport();                
            }

            // Luôn cập nhật content
            $report->work_assignment_id = $model->id;
            $report->reported_at = date('Y-m-d H:i:s');
            $report->content = trim($post['KpiWorkReport']['content'] ?? '');

            if ($report->content === '') {
                return [
                    'content' => '<div class="alert alert-warning">Vui lòng nhập nội dung báo cáo.</div>',
                ];
            }

            // Lưu cả assignment và report
            if ($model->save() && $report->save()) {
                $report = KpiWorkReport::findOne(['work_assignment_id' => $id]);
                if (!$report) {
                    $report = new KpiWorkReport();
                    $report->work_assignment_id = $id;
                }

                return [
                    'forceReload' => '#crud-datatable-pjax',  // PJAX container để reload
                    'title' => "Chi tiết lịch",         // Tiêu đề modal
                    'content' => '<span class="text-success">Đã Báo cáo đã gửi thành công!</span>', // Nội dung modal
                    'footer' => Html::button('Đóng', [
                        'class' => 'btn btn-default',
                        'data-bs-dismiss' => "modal"  // Thuộc tính Bootstrap 5 để đóng modal
                    ])
                ];
               
            } else {
                return [
                    'tcontent' => '<div class="alert alert-danger">Không lưu được dữ liệu!</div>',
                    'footer' => Html::button('Đóng', [
                        'class' => 'btn btn-default pull-left',
                        'data-bs-dismiss' => "modal"
                    ]),
                ];
            }
        }

        // Nếu GET → hiển thị form bình thường
        $report = KpiWorkReport::findOne(['work_assignment_id' => $id]);
                if (!$report) {
                    $report = new KpiWorkReport();
                    $report->work_assignment_id = $id;
                }
        return $this->render('view', [
            'model' => $model,
            'report' => $report,
        ]);
    }



}