<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var $system app\models\AlSystems */

Pjax::begin([
    'id' => 'pjax-jobs-grid-'. $system->id,
    'options' => ['class' => 'pjax-jobs-grid'],
    'timeout' => 5000,
]);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="text-primary mb-0">
        <i class="fas fa-user"></i> Nhân viên: <?= Html::encode($system->staff->name) ?>
    </h6>   
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
            'attribute' => 'start_date',
            'format' => ['datetime', 'php:d/m/Y h:i A'], // h = 12h, H = 24h, A = AM/PM
        ],
        [
            'attribute' => 'end_date',
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
            'contentOptions' => [
                'style' => 'text-align:center; vertical-align:middle;'
            ],
            'headerOptions' => [
                'style' => 'text-align:center;'
            ],
            'label' => 'Trạng thái',
        ],       
    ],

    /** ✅ Layout giữ phân trang & summary ở cuối **/
    /* 'layout' => "{items}\n<div class='d-flex justify-content-between align-items-center grid-footer-bar mt-2 pt-2 border-top'>
        <div>{pager}</div>
        <div class='small text-muted'>{summary}</div>
    </div>",
    'summary' => 'Hiển thị {count}/{totalCount} bản ghi',
 */
    'layout' => "{items}
                <div class='d-flex justify-content-between align-items-center grid-footer-bar mt-2 pt-2 border-top'>
                    <div class='small text-muted'>{summary}</div>
                    <div>{pager}</div>
                </div>",
    'summary' => 'Tổng: {totalCount} dòng dữ liệu',


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

<?php

$js = <<<JS

    $(document).on('ajaxComplete', function (e, xhr) {
        let res = xhr.responseJSON;
        if (res && res.newCount !== undefined) {
            //alert(res.newCount);
            // Cập nhật lại số lượng trong grid cha
            $('#soluong_' + res.staff_id).text(res.newCount);
        }
    });

JS;
$this->registerJs($js);

?>

