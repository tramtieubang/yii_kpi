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
		'id' => 'form-employees',
        'enableClientValidation' => true,
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <div class="row g-4">
        <!-- ===== Khung 1: Thông tin nhân viên ===== -->
        <div class="col-md-6 d-flex">
            <div class="card flex-fill shadow-sm border-0 d-flex flex-column">
                <div class="card-header-info">Thông tin nhân viên</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row g-3 card-body-row-top">
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
										'template' => '
											<div class="d-flex align-items-center mb-0">
											<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/departments/default/create'],
												[
													'title' => 'Thêm phòng ban mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin:-10px 0px 0px 5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-3',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal3',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  . "
											</div>
											<div>{input}</div>
											{hint}{error}",
											// Tắt tự động thêm dấu * mặc định
        									'labelOptions' => ['class' => 'fw-bold', 'encode' => false],
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn phòng ban...',
										'value' => $model->department_id, // giữ giá trị cũ
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal2 .modal-body')"),
										'allowClear' => true,
										'width' => '100%', // width 100% của container
									],
								]);
							?>
	
                        </div>
                        <div class="col-md-6 col-sm-12">
                           <?php
								// Lấy danh sách chức vụ và gán STT
								$items = PositionsForm::find()->all();
								$data = [];
								foreach ($items as $index => $dept) {
									$data[$dept->id] = '<div style="display: flex;">
															<div style="width:24px;">'.($index + 1).'</div> 
															<div style="width:100% !important">'.$dept->name.'</div>
														</div>';
								}

								echo $form->field($model, 'position_id', [
										'template' => '
											<div class="d-flex align-items-center mb-0">
											<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/positions/default/create'],
												[
													'title' => 'Thêm chức vụ mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin:-10px 0px 0px 5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-3',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal3',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  . "
											</div>
											<div>{input}</div>
											{hint}{error}",
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn chức vụ...',
										'value' => $model->position_id, // giữ giá trị cũ
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal2 .modal-body')"),
										'allowClear' => true,
										'width' => '100%', // width 100% của container
									],
								]);
							?>	
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">                          
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
										'template' => '
											<div class="d-flex align-items-center mb-0">
											<label class="control-label fw-bold" style="margin-bottom:0px;">{label}</label> ' . 
											Html::a(
												'<i class="fa fa-plus"></i>',
												['/business-fields/default/create'],
												[
													'title' => 'Thêm lĩnh vực kinh doanh mới',
													'class' => 'btn btn-outline-primary btn-sm rounded-circle',
													'id' => 'btn-add-position',
													'style' => 'margin:-10px 0px 0px 5px; padding:0.25rem 0.35rem; width:17px; height:17px; display:inline-flex; align-items:center; justify-content:center;',
													'role' => 'modal-remote-3',
													'data-pjax' => 0,
													'data-target' => '#ajaxCrudModal3',
													'data-bs-toggle' => 'tooltip',
													'data-bs-placement' => 'top',
												]
											)  ."
											</div>
											<div>{input}</div>
											{hint}{error}",
								])->widget(Select2::classname(), [
									'data' => $data,
									'options' => [
										'placeholder' => 'Chọn lĩnh vực kinh doanh...',
										'value' => $model->business_field_id, // giữ giá trị cũ
									],
									'pluginOptions' => [
										'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
										'dropdownParent' => new JsExpression("$('#ajaxCrudModal2 .modal-body')"),
										'allowClear' => true,
										'width' => '100%', // width 100% của container
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
                <div class="card-header-account">Tài khoản người dùng</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row card-body-row-top">
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
						
						<div class="col-12">
							<?= $form->field($model, 'status', [
									'template' => "{label}\n<div class='input-group'>
										<input type='range' class='form-range' min='-1' max='1' step='1' value='{$model->status}' id='statusRange'>
										{input}
									</div>\n{error}"
								])->label('Trạng thái', ['class' => 'fw-bold']) ?>
							<div class="form-text" id="statusText">
								<?php 
									switch ($model->status) {
										case 1: echo 'Hoạt động'; break;
										case 0: echo 'Không hoạt động'; break;
										case -1: echo 'Ngừng hoạt động'; break;
										default: echo '';
									}
								?>
							</div>
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

.modal-footer {
    margin-top: 0rem !important;
}

/* ===== Card Header kiểu MISA chuyên nghiệp, nhỏ gọn ===== */
.employees-form-form .card-header-info {
    background: linear-gradient(90deg, #a9acacff, #c9dbb6ff);
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.1;
    border-radius: 0.5rem 0.5rem 0 0;
    color: #3d3a3aff;
    margin: 0;
    padding: 0.75rem 0.8rem; /* nhỏ nhất có thể nhưng vẫn đẹp */
   /*  border: 1px solid #ddd;   /* nên có màu để tránh viền quá đậm */ 
}

.employees-form-form .card-header-account {
    background: linear-gradient(90deg, #a9acacff, #b4d7d7ff);
    font-size: 0.9rem;
    font-weight: 600;
    line-height: 1.1;
    border-radius: 0.5rem 0.5rem 0 0;
    color: #3d3a3aff;
    margin: 0;
    padding: 0.75rem 0.8rem; /* nhỏ nhất có thể nhưng vẫn đẹp */
   /*  border: 1px solid #ddd;   /* nên có màu để tránh viền quá đậm */ 
}

/* ===== Card Body gọn ===== */
.employees-form-form .card-body {
    padding: 0rem 0.75rem;     /* giảm padding */
}

/* First Row trong card */
.employees-form-form .card-body .card-body-row-top {
	padding-top: 1rem;
}

/* Row trong card */
.employees-form-form .card-body .row {
    margin-bottom: 0.5rem;      /* khoảng cách giữa các row */
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

<?php
/* $script = <<< JS
    if (!window.statusRangeInitialized) {
    const statusRange = document.getElementById('statusRange');
    const statusText = document.getElementById('statusText');

    statusRange.addEventListener('input', function() {
        let value = parseInt(this.value);
        let text = '';
        switch(value) {
            case 1: text = 'Hoạt động'; break;
            case 0: text = 'Không hoạt động'; break;
            case -1: text = 'Ngừng hoạt động'; break;
        }
        statusText.innerText = text;
        document.getElementById('employeesform-status').value = value;
    });

    window.statusRangeInitialized = true;
}

JS;
$this->registerJs($script); */
?>

<script>
	// cach 2 chua test
 	$(document).off('input', '#statusRange').on('input', '#statusRange', function() {
		let value = parseInt(this.value);
		let text = '';
		switch(value){
			case 1: text = 'Hoạt động'; break;
			case 0: text = 'Không hoạt động'; break;
			case -1: text = 'Ngừng hoạt động'; break;
		}
		$('#statusText').text(text);
		$('#employeesform-status').val(value);
	}); 
</script>