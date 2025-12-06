<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var $system app\models\AlSystems */
/** @var $dataProvider yii\data\ActiveDataProvider */

Pjax::begin([
    'id' => 'pjax-jobs-grid-' . $system->staff_id,
    'timeout' => 5000,
]);
?>
    <?php $form = ActiveForm::begin([
        'id'=>'form_jobs_grid'. $system->staff_id,
        'method'=>'post',
        'action'=>['filter'],
        'options'=>[
            'class'=>'inline-filter',
            'id'=>'filterForm'
        ],
    ]); ?>   

<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="text-primary mb-0">
        <i class="fas fa-user"></i> Nhân viên: <?= Html::encode($system->staff->name) ?>
    </h6>
    <?= Html::button('<i class="fas fa-list-check"></i> Duyệt tùy chọn', [
        'id' => 'btn-custom-approve',
        'title' => 'Duyệt công việc tùy chọn',
        'class' => 'btn btn-sm btn-outline-success',
        'data-url' => Url::to(['/work-assignment/approve/custom-approve', 'staff_id' => $system->staff->staff_id]),
        'data-bs-toggle' => 'tooltip',
        'data-bs-placement' => 'top',
    ]) ?>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'pjax' => false,
    'id' => 'grid-jobs-' . $system->staff_id,
    'columns' => [
        [
            'class' => 'kartik\grid\CheckboxColumn',
            'width' => '20px',
            'checkboxOptions' => function ($model) {
                return ['class' => 'kv-row-checkbox cb-work', 'value' => $model->id];
            },
            'header' => Html::checkBox('checkAll_' . $system->staff_id, false, [
                'class' => 'check-all-jobs',
                'data-grid-id' => $system->staff_id,
            ]),
        ],
        ['class' => 'kartik\grid\SerialColumn'],
        'title',
        'description',
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
            'template' => '{view} {approve} {reject}',
            'width' => '110px',
            'buttons' => [
                'view' => fn($url, $model) => Html::a('<i class="fas fa-eye"></i>', ['/work-assignment/approve/view', 'id' => $model->id], [
                    'class' => 'btn btn-primary btn-sm', 'role' => 'modal-remote', 'title' => 'Xem chi tiết'
                ]),
                'approve' => fn($url, $model) => Html::a('<i class="fas fa-edit"></i>', ['/work-assignment/approve/update', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-success', 'role' => 'modal-remote', 'title' => 'Duyệt'
                ]),
                'reject' => fn($url, $model) => Html::a('<i class="fas fa-times-circle"></i>', ['/work-assignment/approve/reject', 'id' => $model->id], [
                    'class' => 'btn btn-sm btn-outline-warning', 'role' => 'modal-remote', 'title' => 'Từ chối'
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

<?php ActiveForm::end(); ?>

<?php Pjax::end(); ?>

<?php
$this->registerJs(<<<JS
jQuery(function($){
    let gridSelector = "#grid-jobs-{$system->staff_id}";
    
    // CHECK ALL
    $(document).on('change', '.check-all-jobs[data-grid-id="{$system->staff_id}"]', function(){
        let checked = $(this).prop('checked');
        $(gridSelector + ' input.cb-work').prop('checked', checked);
    });
    
    $(document).on('change', gridSelector + ' input.cb-work', function(){
        let table = $(this).closest('table');
        let all = table.find('input.cb-work').length;
        let checked = table.find('input.cb-work:checked').length;
        table.find('.check-all-jobs').prop('checked', all === checked);
    });

    // NÚT DUYỆT TÙY CHỌN    
    $(document).off('click').on('click', '#btn-custom-approve', function(e){
         e.preventDefault();

        let url = $(this).data('url');
        let pks = $(gridSelector + ' input.cb-work:checked').map(function(){ 
            return $(this).val(); 
        }).get();

        if(pks.length === 0){
            Swal.fire({
                icon: 'warning',
                title: 'Chưa chọn công việc!',
                text: 'Vui lòng chọn ít nhất một công việc.',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }

        $.ajax({
            url: url,
            type: 'POST',
            data: {pks: pks.join(',')},
            success: function(response){
                if(response.tcontent){
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: response.tcontent, // nếu muốn hiện thêm nội dung từ server
                        timer: 3000,
                        showConfirmButton: false
                    });
                }

                if(response.forceReload){
                    $.pjax.reload({container: response.forceReload});
                }
            },
            error: function(){
                alert('Lỗi khi gửi dữ liệu!');
            }
        });
    });

});

JS
);
?>
