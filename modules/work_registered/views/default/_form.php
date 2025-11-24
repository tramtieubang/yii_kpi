<?php

use app\models\KpiWorkRegisteredStatus;
use app\modules\kpi\models\KpiKpiForm;
use app\modules\staff\models\StaffForm;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\web\JqueryAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkRegisteredForm */

?>

<style>
.kpi-form-field { margin-bottom: 0 !important; }
.kpi-form-field .form-label { margin-bottom: 2px !important; font-weight: 600; }
.kpi-work-registered-form .card { padding-left: 25px !important; padding-right: 20px !important; }
.row.g-2 { --bs-gutter-x:10px !important; --bs-gutter-y:0px !important; }
.select2-container--bootstrap5 .select2-selection--single {
    height: 34px !important; padding: 0 8px !important; display: flex !important; align-items: center !important;
}
.select2-container .select2-selection__arrow { height: 34px !important; }
.input-group .btn-add { width: 34px !important; height: 34px !important; border-left: 0 !important; display: flex; align-items: center; justify-content: center; padding: 0 !important; }
.kpi-work-registered-form input,
.kpi-work-registered-form textarea,
.kpi-work-registered-form .select2-selection { width: 100% !important; }
.kpi-work-registered-form h4.fw-bold { padding-top:16px; }
</style>

<div class="kpi-work-registered-form">

<?php $form = ActiveForm::begin([
    'id' => 'kpi-work-registered',
    'enableClientValidation' => true,
    'options' => ['autocomplete' => 'off'],
]); ?>

<!-- CARD: ĐĂNG KÝ CÔNG VIỆC -->
<div class="card kpi-card shadow-sm">
    <h4 class="fw-bold mb-3">Đăng ký công việc</h4>
    <div class="row g-2">

        <!-- Nhân viên -->
        <div class="col-md-6 kpi-form-field">
            <?= $form->field($model, 'staff_id', [
                'template' => '{label}<div class="input-group">{input}<button class="btn btn-outline-primary btn-add" role="modal-remote-2" data-url="'.Url::to(['/staff/default/create']).'" data-target="#ajaxCrudModal2" data-pjax="0"><i class="fa fa-plus"></i></button></div>{error}'
            ])->widget(Select2::class, [
                'data' => ArrayHelper::map(StaffForm::find()->all(), 'id', fn($m) => $m->staff_id.' - '.$m->name),
                'options' => ['placeholder' => 'Chọn nhân viên...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')")
                ]
            ]) ?>
        </div>

        <!-- KPI -->
        <div class="col-md-6 kpi-form-field">
            <?= $form->field($model, 'kpi_id', [
                'template' => '{label}<div class="input-group">{input}<button class="btn btn-outline-primary btn-add" role="modal-remote-3" data-url="'.Url::to(['/kpi/default/create']).'" data-target="#ajaxCrudModal3" data-pjax="0"><i class="fa fa-plus"></i></button></div>{error}'
            ])->widget(Select2::class, [
                'data' => ArrayHelper::map(KpiKpiForm::find()->all(), 'id', fn($m) => $m->id.' - '.$m->name),
                'options' => ['placeholder' => 'Chọn KPI...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')")
                ]
            ]) ?>
        </div>

        <!-- Ngày bắt đầu -->
        <div class="col-md-4 kpi-form-field">
            <?= $form->field($model, 'date_start')->input('datetime-local', [
                'value' => $model->date_start ? date('Y-m-d\TH:i', strtotime($model->date_start)) : ''
            ]) ?>
        </div>

        <!-- Ngày kết thúc -->
        <div class="col-md-4 kpi-form-field">
            <?= $form->field($model, 'date_end')->input('datetime-local', [
                'value' => $model->date_end ? date('Y-m-d\TH:i', strtotime($model->date_end)) : ''
            ]) ?>
        </div>

        <!-- Trạng thái -->
        <div class="col-md-4 kpi-form-field">
            <?= $form->field($model, 'status_id')->widget(Select2::class, [
                'data' => ArrayHelper::map(KpiWorkRegisteredStatus::find()->all(), 'id', 'name'),
                'options' => ['placeholder'=>'Chọn trạng thái...'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
                    'width'=>'100%',
                ]
            ]) ?>
        </div>

        <!-- Tên công việc -->
        <div class="col-md-12 kpi-form-field">
            <?= $form->field($model, 'title')->textInput(['placeholder'=>'Nhập tên công việc...']) ?>
        </div>

        <!-- Mô tả -->
        <div class="col-md-12 kpi-form-field">
            <?= $form->field($model, 'description')->textarea(['rows'=>3,'placeholder'=>'Mô tả chi tiết công việc...']) ?>
        </div>
    </div>
</div>

<!-- LỊCH SỬ CÔNG VIỆC -->
<div class="card shadow-sm rounded-3 p-4 mb-3 bg-white">
    <h4 class="mb-3 fw-semibold">Lịch sử công việc</h4>
    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th width="60">#</th>
                <th>Công việc</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($model->kpiWorkRegisteredHistories as $i => $history): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= Html::encode($history->title) ?></td>
                <td><?= $history->date_start ? date('d/m/Y H:i', strtotime($history->date_start)) : '' ?></td>
                <td><?= $history->date_end ? date('d/m/Y H:i', strtotime($history->date_end)) : '' ?></td>
                <?php
                    $labels = ['update'=>['Cập nhật','#FFD700'],'create'=>['Thêm mới','#90EE90']];
                    $label = $labels[$history->action_type][0] ?? $history->action_type;
                    $bg = $labels[$history->action_type][1] ?? '#ffffff';
                ?>
                <td class="text-center"><span style="background:<?= $bg ?>;padding:4px 6px;border-radius:4px"><?= Html::encode($label) ?></span></td>
                <td class="text-center">
                    <?= Html::a('Xoá', '#', [
                        'class'=>'btn btn-sm btn-danger btn-delete',
                        'data-url'=>Url::to(['history-delete','id'=>$history->id])
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php ActiveForm::end(); ?>

<script>
    $(document).on('click', '.btn-delete', function(e){
    e.preventDefault();
    const url = $(this).data('url');
    const row = $(this).closest('tr');

    bootbox.confirm({
        title: "XÁC NHẬN",
        message: "Bạn có chắc chắn muốn xóa dữ liệu này?",
        centerVertical: true,
        buttons:{
            confirm:{label:'ĐỒNG Ý', className:'btn-danger'},
            cancel:{label:'HỦY', className:'btn-secondary'}
        },
        callback:function(result){
            if(result){
                $.post(url, {_csrf: yii.getCsrfToken()})
                .done(function(data){
                    if(data.success){
                        row.remove();
                        bootbox.alert(data.message);
                    } else {
                        bootbox.alert(data.message || 'Có lỗi xảy ra, không xóa được.');
                    }
                }).fail(function(){
                    bootbox.alert('Có lỗi xảy ra, không xóa được.');
                });
            }
        }
    });
});

// Fix mất scroll khi modal Bootbox đóng trong modal gốc
$(document).on('hidden.bs.modal', '.modal', function () {
    if($('.modal.show').length > 0){
        $('body').addClass('modal-open');
    }
});

</script>