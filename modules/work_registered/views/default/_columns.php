<?php
use yii\helpers\Url;

return [
    // Cột số thứ tự
    [
        'class' => 'kartik\grid\SerialColumn',
        'width' => '30px',
    ],

    // Cột hành động: nút bánh răng + dropdown Xem/Sửa/Xóa
    [
        'class' => 'kartik\grid\ActionColumn',
        'header'=>'',
        'template' => '{view} {update} {delete}', // các action
        'dropdown' => true,
        'dropdownOptions' => ['class' => 'float-end'],
        'dropdownButton'=>[
            'label'=>'<i class="fe fe-settings"></i>', // icon bánh răng
            'class'=>'btn btn-sm btn-secondary dropdown-toggle p-0',
        ],
        'vAlign'=>'middle',
        'width' => '50px',
        'urlCreator' => function($action, $model, $key, $index) {
            return Url::to([$action,'id'=>$key]);
        },
        'viewOptions'=>[
            'role'=>'modal-remote',
            'title'=>'Xem',
            'class'=>'dropdown-item'
        ],
        'updateOptions'=>[
            'role'=>'modal-remote',
            'title'=>'Sửa',
            'class'=>'dropdown-item'
        ],
        'deleteOptions'=>[
            'role'=>'modal-remote',
            'title'=>'Xóa',
            'data-confirm'=>false,
            'data-method'=>false,
            'data-request-method'=>'post',
            'class'=>'dropdown-item text-danger'
        ],
    ],

    // Nhóm theo nhân viên
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'employee_id',
        'vAlign'=>'top',
        'value' => function ($model) {
            return $model->employee ? $model->employee->name : null;
        },
        'group'=>true, // nhóm theo nhân viên
        'groupedRow'=>true, // hiển thị grouped row
        'groupOddCssClass'=>'table-active',
        'groupEvenCssClass'=>'table-light',
    ],

    // Ngày thực hiện
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'date_start',
        'label'=>'Ngày bắt đầu',
        'vAlign'=>'top',
        'format' => ['datetime', 'php:d/m/Y H:i'],
        'contentOptions' => ['style'=>'white-space: nowrap;'],
        'headerOptions'=>['style'=>'white-space: nowrap;'],
        'value' => function($model){
            return $model->date_start;
        },
        'sortLinkOptions' => ['class'=>'text-decoration-none'],
    ],

    // Tiêu đề công việc
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'title',
        'vAlign'=>'top',
    ],

    // Mô tả công việc
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'description',
        'vAlign'=>'top',
    ],

    // Trạng thái với màu sắc
    [
        'class'=>'\kartik\grid\DataColumn',
        'attribute'=>'status',
        'vAlign'=>'top',
        'format' => 'raw',
        'value' => function($model) {
            switch($model->status) {
                case 0:
                    return '<span class="badge bg-secondary">Chờ duyệt</span>';
                case 1:
                    return '<span class="badge bg-success">Đã duyệt</span>';
                case 2:
                    return '<span class="badge bg-danger">Hủy</span>';
                default:
                    return '<span class="badge bg-light">Không xác định</span>';
            }
        },
    ],
];
