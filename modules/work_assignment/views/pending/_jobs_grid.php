<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\web\JqueryAsset;

// Đảm bảo jQuery được load trước script
JqueryAsset::register($this);

/** @var $system app\models\AlSystems */
/** @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-jobs-grid-' . $system->id,
    'timeout' => 5000,
]);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="text-primary mb-0">
        <i class="fas fa-user"></i> Nhân viên: <?= Html::encode($system->staff->name) ?>
    </h6>
    <?= Html::a('<i class="fas fa-list-check"></i> Duyệt tùy chọn', ['/work-assignment/pending/create', 'staff_id' => $system->staff->staff_id], [
        'class' => 'btn btn-sm btn-outline-primary',
        'role' => 'modal-remote',
        'title' => 'Duyệt công việc tùy chọn',
    ]) ?>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'pjax' => false,
    'id' => 'grid-jobs-' . $system->id,

    'columns' => [
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'width' => '20px',
            'header' => Html::checkBox('checkAll_' . $system->id, false, [
                'class' => 'check-all-jobs',
                'data-grid-id' => $system->id
            ]),
        ],
        ['class' => 'kartik\grid\SerialColumn'],
        ['attribute' => 'title'],
        ['attribute' => 'description'],
        [
            'attribute' => 'start_date',
            'format' => ['datetime', 'php:d/m/Y h:i A'],
        ],
        [
            'attribute' => 'end_date',
            'format' => ['datetime', 'php:d/m/Y h:i A'],
        ],
        [
            'attribute' => 'status_id',
            'format' => 'raw',
            'value' => function($model) {
                if (!$model->status) return 'N/A';
                $color = $model->status->color ?? '#6c757d';
                return Html::tag('span', Html::encode($model->status->name), [
                    'class' => 'badge',
                    'style' => "background-color: {$color}; color: #fff;"
                ]);
            },
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'width' => '110px',
            'buttons' => [
                'view' => function($url, $model) {
                    return Html::a('<i class="fas fa-eye"></i>', ['/work-assignment/pending/view', 'id' => $model->id], [
                        'class' => 'btn btn-primary btn-sm',
                        'role' => 'modal-remote',
                        'title' => 'Xem chi tiết',
                        'data-toggle' => 'tooltip',  // bắt buộc để Bootstrap tooltip hoạt động
                        'data-placement' => 'top',   // vị trí hiển thị tooltip
                    ]);
                },
                'update' => fn($url, $model) => Html::a('<i class="fas fa-edit"></i>', ['/work-assignment/pending/update', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-success',
                    'role' => 'modal-remote',
                    'title' => 'Duyệt',
                    'data-toggle' => 'tooltip',  // bắt buộc để Bootstrap tooltip hoạt động
                    'data-placement' => 'top',   // vị trí hiển thị tooltip
                ]),
                'delete' => fn($url, $model) => Html::a('<i class="fas fa-times-circle"></i>', ['/work-assignment/pending/delete', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-warning',
                    'role' => 'modal-remote',
                    'title' => 'Từ chối',
                    'data-toggle' => 'tooltip',  // bắt buộc để Bootstrap tooltip hoạt động
                    'data-placement' => 'top',   // vị trí hiển thị tooltip
                ]),
            ],
            'contentOptions' => ['class' => 'text-center'],
        ],
    ],
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
.grid-footer-bar { border-color: #e5e7eb !important; font-size: 13px; }
.grid-footer-bar .pagination { margin: 0; }
table.kv-grid-table tbody tr:hover {
    background-color: #f1f7ff !important;
    transition: background-color 0.2s ease;
}
.kv-grid-table { background-color: #ffffff !important; }
.kv-grid-table td, .kv-grid-table th { border-color: #e5e7eb !important; }
");
?>

<?php
$gridId = json_encode($system->id);
$this->registerJs(<<<JS
jQuery(function($){
    var gridSelector = '#grid-jobs-' + $gridId;

    $(document).on('change', '.check-all-jobs[data-grid-id='+$gridId+']', function() {
        var checked = $(this).prop('checked');
        $(gridSelector + ' input.kv-row-checkbox').prop('checked', checked);
    });

    $(document).on('change', gridSelector + ' input.kv-row-checkbox', function() {
        var grid = $(this).closest('table');
        var allChecked = grid.find('input.kv-row-checkbox').length === grid.find('input.kv-row-checkbox:checked').length;
        grid.find('.check-all-jobs').prop('checked', allChecked);
    });

    // Rebind PJAX
    $(document).on('pjax:end', function() {
        $(document).off('change', '.check-all-jobs[data-grid-id='+$gridId+']');
        $(document).off('change', gridSelector + ' input.kv-row-checkbox');

        $(document).on('change', '.check-all-jobs[data-grid-id='+$gridId+']', function() {
            var checked = $(this).prop('checked');
            $(gridSelector + ' input.kv-row-checkbox').prop('checked', checked);
        });
        $(document).on('change', gridSelector + ' input.kv-row-checkbox', function() {
            var grid = $(this).closest('table');
            var allChecked = grid.find('input.kv-row-checkbox').length === grid.find('input.kv-row-checkbox:checked').length;
            grid.find('.check-all-jobs').prop('checked', allChecked);
        });
    });

});
JS
);
?>
