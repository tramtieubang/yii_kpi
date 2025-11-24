<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var $system app\models\AlSystems */

Pjax::begin([
    'id' => 'pjax-jobs-grid-' . $system->id,
    'options' => ['class' => 'pjax-jobs-grid'],
    'timeout' => 5000,
]);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="text-primary mb-0">
        <i class="fas fa-user"></i> Nhân viên: <?= Html::encode($system->staff->name) ?>
    </h6>
    <?= Html::a('<i class="fas fa-plus"></i> Thêm mới', ['/work_registered/default/create', 'system_id' => $system->id], [
        'class' => 'btn btn-sm btn-outline-primary',
        'role' => 'modal-remote-3',
        'title' => 'Thêm mới profile',
    ]) ?>
</div>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'title',
            //'label' => 'Tiêu đề',
        ],
        [
            'attribute' => 'description',
            //'label' => 'Mô tả',
        ],
        [
            'attribute' => 'date_start',
            'format' => ['datetime', 'php:d/m/Y h:i A'], // h = 12h, H = 24h, A = AM/PM
        ],
        [
            'attribute' => 'date_end',
            //'label' => 'Ngày kết thúc',
             'format' => ['datetime', 'php:d/m/Y h:i A'], // h = 12h, H = 24h, A = AM/PM
        ],
        [
            'attribute' => 'status_id',
            'format' => 'raw', // để render HTML
            'value' => function($model) {
                if (!$model->status) return 'N/A';
                $color = $model->status->color ?? '#6c757d'; // fallback nếu không có color
                return Html::tag('span', Html::encode($model->status->name), [
                    'class' => 'badge',
                    'style' => "background-color: {$color}; color: #fff;"
                ]);
            },
            'label' => 'Trạng thái',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'width' => '110px', // tăng chiều rộng đủ cho 3 nút
            'buttons' => [                
                'view' => function($url, $model, $key) {
                    return Html::a('<i class="fas fa-eye"></i>', ['/work_registered/default/view', 'id' => $model->id], [
                        'class' => 'btn btn-primary btn-sm',
                        'role' => 'modal-remote',
                        'title' => 'Xem chi tiết',
                        'data-bs-toggle' => 'tooltip',
                        'data-bs-placement' => 'top',
                    ]);
                },
                'update' => fn($url, $model) => Html::a('<i class="fas fa-edit"></i>', ['/work_registered/default/update', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-primary',
                    'role' => 'modal-remote-3',
                    'title' => 'Cập nhật profile',
                ]),
                'delete' => fn($url, $model) => Html::a('<i class="fas fa-trash"></i>', ['/work_registered/default/delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-danger',
                    'role' => 'modal-remote-2',
                    'data-request-method' => 'post',
                    'data-confirm-title' => 'Xác nhận xóa?',
                    'data-confirm-message' => 'Bạn có chắc muốn xóa thanh nhôm này?',
                    'title' => 'Xóa',
                ]),
            ],
            'contentOptions' => ['class' => 'text-center'],
        ],
    ],

    /** ✅ Layout giữ phân trang & summary ở cuối **/
    'layout' => "{items}\n<div class='d-flex justify-content-between align-items-center grid-footer-bar mt-2 pt-2 border-top'>
        <div>{pager}</div>
        <div class='small text-muted'>{summary}</div>
    </div>",

    'summary' => 'Hiển thị {count}/{totalCount} bản ghi',
    'responsive' => true,
    'condensed' => true,
    'striped' => false,
    'hover' => true,
]) ?>

<?php Pjax::end(); ?>

<?php
$this->registerCss("
    /* Footer */
    .grid-footer-bar {
        border-color: #e5e7eb !important;
        font-size: 13px;
    }
    .grid-footer-bar .pagination {
        margin: 0;
    }

    /* Hover hiệu ứng */
    table.kv-grid-table tbody tr:hover {
        background-color: #f1f7ff !important;
        transition: background-color 0.2s ease;
    }

    /* Nền trắng cho bảng */
    .kv-grid-table {
        background-color: #ffffff !important;
    }

    /* Viền nhẹ để rõ ràng */
    .kv-grid-table td, .kv-grid-table th {
        border-color: #e5e7eb !important;
    }
");
?>
