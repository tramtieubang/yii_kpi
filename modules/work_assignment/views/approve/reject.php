<?php

use kartik\form\ActiveForm;
use yii\helpers\Html;

$this->title = 'Chi tiết công việc';
$this->params['breadcrumbs'][] = $this->title;

?>

<style>
.kpi-form-field {
    margin-bottom: 8px !important;
}
.kpi-form-field .form-label {
    font-weight: 600;
    margin-bottom: 4px;
}
.form-control-view {
    background: #f8f9fa;
    padding: 7px 12px;
    border-radius: 6px;
    border: 1px solid #dee2e6;
    min-height: 34px;
}
</style>

<div class="kpi-work-registered-view">
    
<?php $form = ActiveForm::begin([
    'id' => 'kpi-work-registered',
    'enableClientValidation' => true,
    'options' => ['autocomplete' => 'off'],
]); ?>

<!-- CARD THÔNG TIN -->
<div class="card kpi-card shadow-sm p-4 mb-3">
    <h4 class="fw-bold mb-3">Thông tin công việc</h4>

    <div class="row g-2">

        <!-- Nhân viên -->
        <div class="col-md-6 kpi-form-field">
            <label class="form-label">Nhân viên</label>
            <div class="form-control-view">
                <?= $model->staff ? $model->staff->staff_id.' - '.$model->staff->name : '(Không có dữ liệu)' ?>
            </div>
        </div>

        <!-- KPI -->
        <div class="col-md-6 kpi-form-field">
            <label class="form-label">KPI</label>
            <div class="form-control-view">
                <?= $model->kpi ? $model->kpi->id.' - '.$model->kpi->name : '(Không có dữ liệu)' ?>
            </div>
        </div>

        <!-- Ngày bắt đầu -->
        <div class="col-md-4 kpi-form-field">
            <label class="form-label">Ngày bắt đầu</label>
            <div class="form-control-view">
                <?= $model->start_date ? date('d/m/Y H:i', strtotime($model->start_date)) : '' ?>
            </div>
        </div>

        <!-- Ngày kết thúc -->
        <div class="col-md-4 kpi-form-field">
            <label class="form-label">Ngày kết thúc</label>
            <div class="form-control-view">
                <?= $model->end_date ? date('d/m/Y H:i', strtotime($model->end_date)) : '' ?>
            </div>
        </div>

        <!-- Trạng thái -->
        <div class="col-md-4 kpi-form-field">
            <label class="form-label">Trạng thái</label>
            <div class="form-control-view">
                <?= $model->status ? $model->status->name : '' ?>
            </div>
        </div>

        <!-- Tên công việc -->
        <div class="col-md-12 kpi-form-field">
            <label class="form-label">Tên công việc</label>
            <div class="form-control-view">
                <?= Html::encode($model->title) ?>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="col-md-12 kpi-form-field">
            <label class="form-label">Nội dung</label>
            <div class="form-control-view" style="min-height:70px;">
                <?= nl2br(Html::encode($model->description)) ?>
            </div>
        </div>

        <!-- Nội dung -->
        <div class="col-md-12 kpi-form-field">
            <label class="form-label">Lý do từ chối</label>            
            <textarea name="reject_reason" class="form-control" rows="3" placeholder="Nhập lý do từ chối..."></textarea>
        </div>

    </div>
</div>

<!-- LỊCH SỬ -->
<!-- <div class="card shadow-sm rounded-3 p-4 mb-3 bg-white">
    <h4 class="mb-3 fw-semibold">Lịch sử công việc</h4>

    <table class="table table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
            <tr>
                <th style="width:60px">#</th>
                <th>Công việc</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Trạng thái</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($model->kpiWorkRegisteredHistories as $i => $history): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($history->title) ?></td>
                <td><?= $history->start_date ? date('d/m/Y H:i', strtotime($history->start_date)) : '' ?></td>
                <td><?= $history->end_date ? date('d/m/Y H:i', strtotime($history->end_date)) : '' ?></td>

                <?php
                switch ($history->action_type) {
                    case 'update':
                        $label = 'Cập nhật';
                        $bg = '#FFD700';
                        break;
                    case 'create':
                        $label = 'Thêm mới';
                        $bg = '#90EE90';
                        break;
                    default:
                        $label = $history->action_type;
                        $bg = '#f8d7da';
                        break;
                }
                ?>

                <td class="text-center">
                    <span style="
                        background-color: <?= $bg ?>;
                        padding: 3px 8px;
                        border-radius: 4px;
                        color:#000;
                    ">
                        <?= $label ?>
                    </span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div> -->

<?php ActiveForm::end(); ?>

</div>
