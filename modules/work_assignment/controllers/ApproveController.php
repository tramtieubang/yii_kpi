<?php

namespace app\modules\work_assignment\controllers;

use app\common\helpers\CommonSQL;
use app\models\KpiWorkRegisteredStatus;
use app\modules\work_assignment\models\KpiWorkAssignmentForm;
use app\modules\work_assignment\models\KpiWorkRegisteredSearch;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\filters\AccessControl;

/**
 * DefaultController implements the CRUD actions for KpiWorkAssignmentForm model.
 */
class ApproveController extends Controller
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
                                Html::button('<i class="fa fa-edit"></i> Duyệt',['class'=>'btn btn-success','type'=>"submit"])
        
                ];         
            }

            // Lưu thành công → Không đóng form, giữ nguyên form đang mở
            if($model->load($request->post())){
            		                
                $oldModel = clone $model;

                // $model->save()
                $model->status_id = 2;
                $model->save();

                // Lấy màu từ bảng trạng thái hoặc mặc định
                $statusModel = KpiWorkRegisteredStatus::findOne($model->status_id);
                $model->color = $statusModel->color ?? '#3788d8';
                CommonSQL::saveRegisteredHistory($oldModel, $model, 'assign');

                // Luu lich chinh thuc tu lich dang ky
                CommonSQL::approve($model);
                //dd($model);
                
                //$modelAssignment = KpiWorkAssignmentForm::findOne($id);
                $modelAssignment = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                CommonSQL::saveAssignmentHistory(null, $modelAssignment, 'assign');  
                
                // Đếm lại số dòng grid con
                $searchModel = new KpiWorkRegisteredSearch();
                $searchModel->staff_id = $model->staff_id;
                $dataProvider = $searchModel->searchChild(Yii::$app->request->queryParams);
                $count = $dataProvider->getTotalCount();

                return [
                    //'forceReload' => false,       // ✔ KHÔNG reload toàn bộ grid
                    'forceReload'=>'#pjax-jobs-grid-'.$model->staff_id,
                    'newCount' => $count,
                    'staff_id'  => $model->staff_id,
                    'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
                    'forceClose' => true,
                    'tcontent' => 'Duyệt thành công!',
                ];
            }
        }
       
    }

	public function actionReject($id)
	{
		$request = Yii::$app->request;
		$model = $this->findModel($id);

		if ($request->isAjax) {

			Yii::$app->response->format = Response::FORMAT_JSON;

			/** ======================
			 *  CASE 1: Hiển thị form
			 *  ====================== */
			if ($request->isGet) {
				return [
					'title'   => 'Từ chối',
					'content' => $this->renderAjax('reject', [
						'model' => $model,
					]),
					'footer' =>
						Html::button('Đóng lại', [
							'class' => 'btn btn-default pull-left',
							'data-bs-dismiss' => "modal"
						]) .
						Html::button('<i class="fas fa-times-circle text-danger"></i> Từ chối', [
							'class' => 'btn btn-warning',
							'type'  => "submit"
						]),
				];
			}

			/** ======================
			 *  CASE 2: Submit form
			 *  ====================== */
			if ($request->post()) {
				//dd($model);
				$oldModel = clone $model;

				// Lý do từ chối từ textarea
				$rejectReason = $request->post('reject_reason');

				// Cập nhật trạng thái
				$model->status_id = 3;
				$model->save(false);

				// Cập nhật màu trạng thái
				$statusModel = KpiWorkRegisteredStatus::findOne($model->status_id);
				$model->color = $statusModel->color ?? '#3788d8';

				// Lưu lịch sử
				CommonSQL::saveRegisteredHistory($oldModel, $model, 'reject', [
					'reject_reason' => $rejectReason
				]);

				// TRẢ VỀ JSON ĐÚNG CHUẨN
				return [
					'forceClose' => true,
					'forceReload' => '#pjax-jobs-grid-' . $model->staff_id,
					'system_id' => $model->id
				];
			}

			/** ======================
			 *  CASE 3: load() thất bại → render lại form để tránh lỗi ModalRemote
			 *  ====================== */
			return [
				'title'   => 'Từ chối',
				'content' => $this->renderAjax('reject', [
					'model' => $model,
				]),
				'footer' =>
					Html::button('Đóng lại', [
						'class' => 'btn btn-default pull-left',
						'data-bs-dismiss' => "modal"
					]) .
					Html::button('<i class="fas fa-times-circle text-danger"></i> Từ chối', [
						'class' => 'btn btn-warning',
						'type'  => "submit"
					]),
			];
		}
	}

	public function actionCustomApprove($staff_id)
    {
        //$request = Yii::$app->request;
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

       // Lấy danh sách ID được chọn
        $pks = array_filter(explode(',', Yii::$app->request->post('pks', '')), 'strlen');

        //dd($pks);
        // ✔ Xử lý duyệt
        foreach ($pks as $pk) {
            $model = $this->findModel($pk);
            $old   = clone $model;

            try {
                $model->status_id = 2;
                $model->save(false);

                // màu
                $status = KpiWorkRegisteredStatus::findOne($model->status_id);
                $model->color = $status->color ?? '#3788d8';

                // lưu lịch sử
                CommonSQL::saveRegisteredHistory($old, $model, 'assign');
                CommonSQL::approve($model);

                // lưu lịch sử phân công
                $assign = KpiWorkAssignmentForm::findOne(['work_registered_id' => $model->id]);
                if ($assign) {
                    CommonSQL::saveAssignmentHistory(null, $assign, 'assign');
                }

            } catch (\Throwable $e) {
                Yii::error($e->getMessage());
                return [
                    'forceReload' => '#pjax-jobs-grid-' . $staff_id,
                    'tcontent'    => '<span class="text-danger">Lỗi xử lý!</span>',
                ];
            }
        }



         // Đếm lại số dòng grid con
        $searchModel = new KpiWorkRegisteredSearch();
        $searchModel->staff_id = $model->staff_id;
        $dataProvider = $searchModel->searchChild(Yii::$app->request->queryParams);
        $count = $dataProvider->getTotalCount();
        /* return [                    
            'system_id' => $model->id,    // ✔ Gửi ID về để mở lại expand row
            'forceClose' => true,
        ]; */

        return [
            'forceReload' => '#pjax-jobs-grid-' . $staff_id,
            'newCount' => $count,
            'staff_id'  => $staff_id,                    
            'tcontent' => 'Duyệt thành công'
        ];            
    }

    protected function findModel($id)
    {
        $model = KpiWorkRegisteredForm::findOne($id); // đổi theo model của bạn
        if ($model === null) {
            throw new \yii\web\NotFoundHttpException("Không tìm thấy công việc #$id");
        }
        return $model;
    }

    public function actionFilter()
{
    $searchModel = new KpiWorkRegisteredSearch();

    // Lấy dữ liệu POST
    $postData = Yii::$app->request->post();

    // Nếu POST rỗng → không làm gì hết, chỉ render view mặc định
    if (empty($postData)) {
        // Lấy data mặc định theo query hoặc không lọc gì
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    // Nếu có POST → thực hiện filter
    $dataProvider = $searchModel->search($postData);

    return $this->render('index', [
        'searchModel'  => $searchModel,
        'dataProvider' => $dataProvider,
    ]);
}



} // end class