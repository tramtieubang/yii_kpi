<?php

use app\models\KpiWorkReport;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkAssignment */
?>

<div class="assignment-view-detail">

    <div class="card shadow mb-4 border-0">
        <div class="card-header text-white" style="background: linear-gradient(90deg,#0d6efd,#0a58ca); font-weight: bold;">
            <h5 class="mb-0"><i class="fas fa-tasks"></i> Thông tin công việc</h5>
        </div>
        <div class="card-body">
            <div class="row mb-2">
                <div class="col-md-4"><strong>Nhân viên:</strong></div>
                <div class="col-md-8"><?= Html::encode($model->staff->name ?? '-') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>KPI:</strong></div>
                <div class="col-md-8"><?= Html::encode($model->kpi->title ?? '-') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Trạng thái:</strong></div>
                <div class="col-md-8">
                    <span style="color:<?= $model->status->color ?? '#000' ?>; font-weight: bold;">
                        <?= Html::encode($model->status->name ?? '-') ?>
                    </span>
                </div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Ngày bắt đầu:</strong></div>
                <div class="col-md-8"><?= Yii::$app->formatter->asDatetime($model->start_date, 'php:d/m/Y H:i:s') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Ngày kết thúc:</strong></div>
                <div class="col-md-8"><?= Yii::$app->formatter->asDatetime($model->end_date, 'php:d/m/Y H:i:s') ?></div>
            </div>
            <div class="row mb-2">
                <div class="col-md-4"><strong>Ngày phân công:</strong></div>
                <div class="col-md-8"><?= Yii::$app->formatter->asDatetime($model->assigned_at, 'php:d/m/Y H:i:s') ?></div>
            </div>
        </div>
    </div>

    <!-- Form báo cáo công việc -->
    <div class="card shadow border-0">
        <div class="card-header text-white" style="background-color:#198754; font-weight: bold;">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Báo cáo công việc</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info rounded">
                <b>Công việc:</b> <?= Html::encode($model->kpi->title ?? '-') ?><br>
                <b>Deadline:</b> <?= Yii::$app->formatter->asDatetime($model->end_date, 'php:d/m/Y H:i') ?>
            </div>

            <?php $form = ActiveForm::begin(); ?>

            <?php 
                $report = new KpiWorkReport();  
                $report->work_assignment_id = $model->id;
                echo $form->field($report, 'content')->textarea([
                    'rows' => 5,
                    'placeholder' => 'Nhập nội dung báo cáo chi tiết...'
                ]) 
            ?>

            <div class="d-flex justify-content-end mt-3">
                <?= Html::submitButton('<i class="fas fa-paper-plane"></i> Gửi báo cáo', ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
