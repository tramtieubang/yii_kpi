<?php
use yii\helpers\Url;

return [
    [
        'class' => 'kartik\grid\CheckboxColumn',
        'width' => '20px',
    ],
    [
        'class' => 'kartik\grid\ActionColumn',
        'header'=>'',
        'template' => '{view} {update} {delete} {logout}',  
        'dropdown' => true,
        'dropdownOptions' => ['class' => 'float-right'],
        'dropdownButton'=>[
            'label'=>'<i class="fe fe-settings floating"></i>',
            'class'=>'btn dropdown-toggle p-0'
        ],
        'vAlign'=>'middle',
        'width' => '20px',
        'urlCreator' => function($action, $model, $key, $index) {
        	return Url::to([$action,'id'=>$key]);
        },
        'visibleButtons' => [
            'view' => function ($model, $key, $index) {
                return Yii::$app->params['showView'];
            },
        ],
        'viewOptions'=>['role'=>'modal-remote','title'=>'View','title'=>'Xem',
               'class'=>'btn ripple btn-primary btn-sm',
              'data-bs-placement'=>'top',
              'data-bs-toggle'=>'tooltip-primary'],
        'updateOptions'=>['role'=>'modal-remote','title'=>'Sửa', 
            'class'=>'btn ripple btn-info btn-sm',
            'data-bs-placement'=>'top',
            'data-bs-toggle'=>'tooltip-info'],
        'deleteOptions'=>['role'=>'modal-remote','title'=>'Xóa', 
                      'data-confirm'=>false, 'data-method'=>false,// for overide yii data api
                      'data-request-method'=>'post',
                      'data-toggle'=>'tooltip',
                      'data-confirm-title'=>'Xác nhận xóa dữ liệu?',
                      'data-confirm-message'=>'Bạn có chắc chắn thực hiện hành động này?',
                       'class'=>'btn ripple btn-secondary btn-sm',
                       'data-bs-placement'=>'top',
                       'data-bs-toggle'=>'tooltip-secondary',
                       'data-modal-size' => 'large',
                    ], 
            // ⚙️ Thêm nút Đăng xuất từ xa
        'buttons' => [
            'logout' => function ($url, $model) {
                if ($model->is_Active) {
                    return \yii\helpers\Html::a(
                        '<i class="fa fa-sign-out"></i> Đăng xuất từ xa',
                        ['logout-device', 'id' => $model->id],
                        [
                            'title' => 'Đăng xuất người dùng khỏi thiết bị này',
                            'data-confirm' => 'Bạn có chắc muốn đăng xuất người dùng khỏi thiết bị này?',
                            'data-method' => 'post',
                            'class' => 'dropdown-item text-danger', // ✅ đồng nhất giao diện
                        ]
                    );
                }
                return ''; // Không hiện nếu session không hoạt động
            },
        ],
    ],
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],      
    [
        'attribute' => 'user.username',
        'label' => 'Tài khoản',
        'value' => function($model){ return $model->user->username ?? ($model->user_id); }
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'device_name',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'ip_address',
        'contentOptions' => ['style'=>'max-width:150px;'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'user_agent',
        //dùng StringHelper::truncate() để rút ngắn chuỗi:
        //'value'=>fn($model)=>\yii\helpers\StringHelper::truncate($model->user_agent, 80),
        //'contentOptions' => ['style'=>'max-width:250px; overflow:hidden; white-space:nowrap; text-overflow:ellipsis;'],
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'login_time',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'logout_time',
    ],
    [
        'class'=>'\kartik\grid\DataColumn',
        'label' => 'Trạng thái',
        'value' => function($model){ return $model->getStatusLabel(); }
    ],
   
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'login_time',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'last_activity',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'logout_time',
    // ],
    // [
        // 'class'=>'\kartik\grid\DataColumn',
        // 'attribute'=>'revoked_by_admin',
    // ],
];   