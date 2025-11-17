<?php

use app\modules\business_fields\models\BusinessFieldsForm;
use app\modules\departments\models\DepartmentsForm;
use app\modules\positions\models\PositionsForm;
use kartik\select2\Select2;
use kartik\date\DatePicker;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\employees\models\EmployeesForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-form-form">

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => true,
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <div class="row g-4">
        <!-- ===== Khung 1: Thông tin nhân viên ===== -->
        <div class="col-md-6 d-flex">
            <div class="card flex-fill shadow-sm border-0 d-flex flex-column">
                <div class="card-header">Thông tin nhân viên</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row g-3">
                        <div class="col-md-6">
							<?= $form->field($model, 'name', [
									'template' => "{label}<span class='text-danger'> *</span>\n" .
												"<div class='input-group'>
														<span class='input-group-text'>👤</span>{input}
												</div>\n{error}",
									'labelOptions' => ['class' => 'fw-bold'] // giữ dấu *
								])
								->textInput(['maxlength' => true, 'placeholder' => 'Họ và tên đầy đủ']) ?>
                        </div>
                        <div class="col-md-6">
                           <?= $form->field($model, 'email', [
									'template' => "{label}<span class='text-danger'> *</span>\n" .
												"<div class='input-group'>
														<span class='input-group-text'>📧</span>{input}
												</div>\n{error}",
									'labelOptions' => ['class' => 'fw-bold'] // giữ dấu *
								])
								->textInput(['maxlength' => true, 'placeholder' => 'Email liên hệ']) ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone', [
                                    'template' => "{label}\n<div class='input-group'><span class='input-group-text'>📞</span>{input}</div>\n{error}"
                                ])->textInput(['maxlength' => true, 'placeholder' => 'Số điện thoại'])
                                ->label(null, ['class' => 'fw-bold']) ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'hire_date')->widget(DatePicker::class, [
                                'options' => ['placeholder' => 'Chọn ngày tuyển dụng...', 'class' => 'form-control'],
                                'pluginOptions' => [
                                    'autoclose' => true,
                                    'format' => 'dd/mm/yyyy',
                                    'todayHighlight' => true,
                                ],
                            ])->label(null, ['class' => 'fw-bold']) ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <?php
								// Lấy danh sách phòng ban và gán STT
								$departments = DepartmentsForm::find()->all();
								$data = [];
								foreach ($departments as $index => $dept) {
									$data[$dept->id] = '<div style="display: flex;">
															<div style="width:24px;">'.($index + 1).'</div> 
															<div>'.$dept->name.'</div>
														</div>';
								}

								echo $form->field($model, 'department_id', [
										'template' => '<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/departments/default/create'],
												[
													'title' => 'Thêm phòng ban mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin-left:5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-2',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal2',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  . "{input}{hint}{error}",
											// Tắt tự động thêm dấu * mặc định
        									'labelOptions' => ['class' => 'fw-bold', 'encode' => false],
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn phòng ban...',
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
										'allowClear' => true,
									],
								]);
							?>
	
                        </div>
                        <div class="col-md-6">
                           <?php
								// Lấy danh sách chức vụ và gán STT
								$items = PositionsForm::find()->all();
								$data = [];
								foreach ($items as $index => $dept) {
									$data[$dept->id] = '<div style="display: flex;">
															<div style="width:24px;">'.($index + 1).'</div> 
															<div>'.$dept->name.'</div>
														</div>';
								}

								echo $form->field($model, 'position_id', [
										'template' => '<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/positions/default/create'],
												[
													'title' => 'Thêm chức vụ mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin-left:5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-2',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal2',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  . "{input}{hint}{error}",
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn chức vụ...',
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
										'allowClear' => true,
									],
								]);
							?>	
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-12">                          
							<?php
								// Lấy danh sách linh vuc kinh doanh và gán STT
								$items = BusinessFieldsForm::find()->all();
								$data = [];
								foreach ($items as $index => $dept) {
									$data[$dept->id] = '<div style="display: flex;">
															<div style="width:24px;">'.($index + 1).'</div> 
															<div>'.$dept->name.'</div>
														</div>';
								}

								echo $form->field($model, 'business_field_id', [
										'template' => '<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/business-fields/default/create'],
												[
													'title' => 'Thêm lĩnh vực kinh doanh mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin-left:5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-2',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal2',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  . "{input}{hint}{error}",
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn lĩnh vực kinh doanh...',
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
										'allowClear' => true,
									],
								]);
							?>	
                        </div>
                    </div>

                    <?php if (!Yii::$app->request->isAjax): ?>
                        <div class="mt-auto text-end">
                            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', [
                                'class' => $model->isNewRecord ? 'btn btn-success fw-bold' : 'btn btn-primary fw-bold'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ===== Khung 2: Tài khoản người dùng ===== -->
        <div class="col-md-6 d-flex">
            <div class="card flex-fill shadow-sm border-0 d-flex flex-column">
                <div class="card-header">Tài khoản người dùng</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row g-3">
                        <div class="col-12">
                            <?= $form->field($model, 'username', [
                                    'template' => "{label}\n<div class='input-group'><span class='input-group-text'>👤</span>{input}</div>\n{error}"
                                ])->textInput(['maxlength' => true, 'placeholder' => 'Tên đăng nhập'])
                                ->label(null, ['class' => 'fw-bold']) ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'password', [
                                    'template' => "{label}\n<div class='input-group'><span class='input-group-text'>🔒</span>{input}</div>\n{error}"
                                ])->passwordInput(['maxlength' => true, 'placeholder' => 'Mật khẩu'])
                                ->label(null, ['class' => 'fw-bold']) ?>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'confirm_password', [
                                    'template' => "{label}\n<div class='input-group'><span class='input-group-text'>🔒</span>{input}</div>\n{error}"
                                ])->passwordInput(['maxlength' => true, 'placeholder' => 'Xác nhận mật khẩu'])
                                ->label(null, ['class' => 'fw-bold']) ?>
                        </div>
                    </div>

                    <?php if (!Yii::$app->request->isAjax): ?>
                        <div class="mt-auto text-end">
                            <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', [
                                'class' => $model->isNewRecord ? 'btn btn-success fw-bold' : 'btn btn-primary fw-bold'
                            ]) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<style>
/* ===== Card Header kiểu MISA chuyên nghiệp, nhỏ gọn ===== */
.employees-form-form .card-header {
    background: linear-gradient(90deg, #c4c9cbff, #e8ebebff); /* gradient chuyên nghiệp */
    font-size: 0.9rem;          /* chữ nhỏ gọn */
    font-weight: 600;            /* in đậm vừa đủ */
    padding: 0rem 0.75rem;    /* padding trên/dưới nhỏ nhất */
	margin: 0;
    line-height: 1;            /* khoảng cách dòng vừa đủ */
    border-radius: 0.5rem 0.5rem 0 0;
    color: #3d3a3aff;
}

/* ===== Card Body gọn ===== */
.employees-form-form .card-body {
    padding: 0.4rem 0.75rem;     /* giảm padding */
}

/* Row trong card */
.employees-form-form .card-body .row {
    margin-bottom: 0.4rem;      /* khoảng cách giữa các row */
}

/* Input Group Text (icon) */
.input-group-text {
    background-color: #f8f9fa;
    border-radius: 0.35rem 0 0 0.35rem;
}

/* Button */
.employees-form-form .btn {
    padding: 0.45rem 1.2rem;
    font-size: 0.9rem;
}

/* Footer button nâng lên một chút */
.employees-form-form .mt-auto {
    margin-top: 0.3rem;
}

</style>
