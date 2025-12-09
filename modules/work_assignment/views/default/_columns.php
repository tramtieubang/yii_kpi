<?php

use app\modules\work_assignment\models\KpiWorkAssignmentSearch;
use kartik\grid\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;

return [
     /* [
        'class' => 'kartik\grid\ExpandRowColumn',
        'width' => '50px',
        'value' => fn() => GridView::ROW_COLLAPSED,
        'detail' => function($model) {
            $searchModel = new KpiWorkAssignmentSearch();
            $searchModel->staff_id = $model->staff_id;
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return Yii::$app->controller->renderPartial('_jobs_grid', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
                'system' => $model,
            ]);
        },
        'expandOneOnly' => true,
    ], */
    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'50px',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
            // ❗ Đây là nơi gọi Grid con (_jobs_grid.php)
            // $model là hàng cha hiện tại
            //$jobs = $model->getJobs()->all(); // relation trong model KpiWorkAssignmentForm
             
           /*  $dataProvider = new ArrayDataProvider([
                'allModels' => $jobs,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'attributes' => ['start_date'],
                    'defaultOrder' => ['start_date' => SORT_DESC], // giảm dần
                ],
            ]);  */

            $searchModel = new KpiWorkAssignmentSearch();
            $searchModel->staff_id = $model->staff_id;  // hoặc lấy từ user login
            /* $searchModel = KpiWorkAssignmentSearch::find()
                    ->where(['staff_id' => $model->staff_id])
                    ->one();
 */
            // Lấy dữ liệu POST
            $postData = Yii::$app->request->post();

            // Truyền dữ liệu POST vào search model
            $dataProvider = $searchModel->searchChild($postData ?: Yii::$app->request->queryParams); 

            return $this->render('_jobs_grid', [
                //'jobs' => $jobs,
                'system' => $model, // thêm dòng này
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]);
        },
        'expandOneOnly'=>true,
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],
    [
        'label' => 'Nhân viên',
        'value' => fn($m) => $m->staff ? $m->staff->name : 'N/A',        
    ], 
    [
        'label' => 'Phòng ban',
        'value' => fn($m) => $m->staff->department ? $m->staff->department->name : 'N/A',        
    ],  
    [
        'label' => 'Số công việc',
        //'id' => 'soluong_'.$m->staff_id,
        'value' => function ($m) {
            $searchModel = new KpiWorkAssignmentSearch();
            $searchModel->staff_id = $m->staff_id;
            /* $searchModel = KpiWorkAssignmentSearch::find()
                    ->where(['staff_id' => $m->staff_id])
                    ->one();
 */
            $postData = Yii::$app->request->post();
            // Apply search
            $dataProvider = $searchModel->searchChild($postData ?: Yii::$app->request->queryParams);

            // Lấy tổng số bản ghi sau khi lọc
            //return $dataProvider->getTotalCount();
            $count = $dataProvider->getTotalCount();

            // GÁN ID CHO Ô
            return "<span id='soluong_{$m->staff_id}' class='soluong-cell'>{$count}</span>";
    
        },
        'format' => 'raw',

        // ==== thêm width và căn giữa ====
        'headerOptions' => [
            'style' => 'width:100px; text-align:center; white-space:nowrap;',
        ],
        'contentOptions' => [
            'style' => 'text-align:center; width:100px; font-weight:bold;',
        ],
    ]
   /*  [
        'class' => 'kartik\grid\ActionColumn',
        'template' => '{view} {update} {delete}',
        'width' => '110px',
        'buttons' => [
            'view' => fn($url, $model) => Html::a('<i class="fas fa-eye"></i>', $url, [
                'class' => 'btn btn-sm btn-outline-info',
                'role' => 'modal-remote',
                'title' => 'Xem chi tiết',
            ]),
            'update' => fn($url, $model) => Html::a('<i class="fas fa-edit"></i>', $url, [
                'class' => 'btn btn-sm btn-outline-primary',
                'role' => 'modal-remote',
                'title' => 'Cập nhật',
            ]),
            'delete' => fn($url, $model) => Html::a('<i class="fas fa-trash"></i>', $url, [
                'class' => 'btn btn-sm btn-outline-danger',
                'role' => 'modal-remote-2',
                'data-request-method' => 'post',
                'data-confirm-title' => 'Xác nhận xóa?',
                'data-confirm-message' => 'Bạn có chắc muốn xóa công việc '.$model->title.' này?',
                'title' => 'Xóa',
            ]),
        ],
    ],
 */

];   