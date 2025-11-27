<?php

use app\models\KpiWorkAssignment;
use app\models\KpiWorkAssignmentStatus;
use app\models\KpiWorkRegisteredStatus;
use app\models\KpiWorkReport;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkAssignment */
?>

<div class="assignment-view-detail">

   <?php $form = ActiveForm::begin([
        'id' => 'form-report-assignment',
        'action' => ['calendar/report', 'id' => $model->id], // ⬅️ quan trọng
        'enableAjaxValidation' => false,
        'options' => ['data-pjax' => true] // Quan trọng
    ]); ?>

    <div class="card shadow mb-4 border-0">
        <div class="card-header text-white" style="background: linear-gradient(90deg,#e3e3e6ff,#8dc6e4ff); font-weight: bold;">
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
         <div class="card-header text-white" style="background: linear-gradient(90deg,#e3e3e6ff,#0bf8174c); font-weight: bold;">
            <h5 class="mb-0"><i class="fas fa-file-alt"></i> Báo cáo công việc</h5>
        </div>
        <div class="card-body">
            <div class="alert alert-info rounded">
                <b>Công việc:</b> <?= Html::encode($model->title ?? '-') ?><br>
                <b>Ngày kết thúc công việc:</b> <?= Yii::$app->formatter->asDatetime($model->end_date, 'php:d/m/Y H:i') ?>
            </div>
            <?php                 
                echo $form->field($report, 'content')->textarea([
                    'rows' => 5,
                    'placeholder' => 'Nhập nội dung báo cáo chi tiết...'
                ]) 
            ?>
            <?php 
                //$assignment = new KpiWorkAssignment();  
                //$assignment->status_id = $model->status_id;

                echo $form->field($model, 'status_id')->label('Trạng thái')->widget(Select2::classname(), [
                    'options' => ['placeholder' => 'Chọn trạng thái...'],

                    'data' => ArrayHelper::map(
                        KpiWorkAssignmentStatus::find()->all(),
                        'id',
                        function($model) {
                            // return markup cho dropdown
                            return '
                                <div style="display: flex; align-items: center;">
                                    <div style="
                                        width: 14px; 
                                        height: 14px; 
                                        background: ' . htmlspecialchars($model->color) . ';
                                        border: 1px solid #ccc; 
                                        border-radius: 3px;
                                        margin-right: 6px;
                                    "></div>

                                    <div style="width: 30px; margin-right: 6px;">
                                        ' . $model->id . '
                                    </div>

                                    <div>' . htmlspecialchars($model->name) . '</div>
                                </div>
                            ';
                        }
                    ),

                    'pluginOptions' => [
                        'escapeMarkup' => new JsExpression('function(markup) { return markup; }'), // dùng HTML
                        'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
                        'allowClear' => true,
                        'templateResult' => new JsExpression('function(item) { return item.text; }'), // hiển thị HTML custom
                        'templateSelection' => new JsExpression('function(item) { return item.text; }'), // hiển thị selection
                    ],
                ]);

            ?>

            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<<JS
$(document).on('click', '#btn-report-assignment', function(e){
    e.preventDefault();

    var form = $('#form-report-assignment');

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: form.serialize(),
        dataType: 'json',
        success: function(data){
            // 🔔 Hiện thông báo thành công (nếu có)
           // Cập nhật modal nếu server trả content
            if(data.content){
                $('#ajaxCrudModal .modal-body').html(data.content);
            }
            if(data.footer){
                $('#ajaxCrudModal .modal-footer').html(data.footer);
            }
            if(data.title){
                $('#ajaxCrudModal .modal-title').html(data.title);
            }

            // ⏱ Chờ 1.2 giây trước khi đóng modal
            setTimeout(function(){
                // 🔹 Đóng modal nếu có flag
                if(data.closeModal){
                    $('#ajaxCrudModal').modal('hide');
                }

                // 🔹 Reload PJAX nếu có container
                if(data.forceReload){
                    if($.pjax && $(data.forceReload).length > 0){
                        $.pjax.reload({container: data.forceReload});
                    } else {
                        location.reload(); // fallback reload toàn trang
                    }
                }

                // 🔹 Reload FullCalendar nếu cần
                if(data.refreshCalendar && window.calendarInstance){
                    window.calendarInstance.refetchEvents();
                }
            }, 1200); // 1.2 giây
        },
        error: function(err){
            console.log(err);
            alert('Có lỗi xảy ra!');
        }
    });
});
JS;

$this->registerJs($js);

?>
