<?php

use app\modules\business_fields\models\BusinessFieldsForm;
use app\modules\department\models\DepartmentForm;
use app\modules\positions\models\PositionsForm;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\Staff\models\StaffForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="staff-form-form">

    <?php $form = ActiveForm::begin([
        'id' => 'form-Staff-view',
        'enableClientValidation' => false,
        'options' => ['autocomplete' => 'off'],
    ]); ?>

    <div class="row g-4">
        <!-- Thông tin nhân viên -->
        <div class="col-md-6 d-flex">
            <div class="card flex-fill shadow-sm border-0 d-flex flex-column">
                <div class="card-header-info fw-bold">Thông tin nhân viên</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'name')->textInput([
                                'readonly' => true
                            ])->label('Họ và tên') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'email')->textInput([
                                'readonly' => true
                            ])->label('Email') ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'phone')->textInput([
                                'readonly' => true
                            ])->label('Số điện thoại') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'hire_date')->textInput([
                                'readonly' => true,
                                'value' => Yii::$app->formatter->asDate($model->hire_date, 'php:d/m/Y')
                            ])->label('Ngày tuyển dụng') ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <?= $form->field($model, 'department_id')->widget(Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(DepartmentForm::find()->all(), 'department_id', 'name'),
                                'options' => [
                                    'placeholder' => 'Phòng ban...',
                                    'disabled' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '100%',
                                ],
                            ])->label('Phòng ban') ?>
                        </div>
                        <div class="col-md-6">
                            <?= $form->field($model, 'position_id')->widget(Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(PositionsForm::find()->all(), 'position_id', 'name'),
                                'options' => [
                                    'placeholder' => 'Chức vụ...',
                                    'disabled' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '100%',
                                ],
                            ])->label('Chức vụ') ?>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-12">
                            <?= $form->field($model, 'business_field_id')->widget(Select2::classname(), [
                                'data' => \yii\helpers\ArrayHelper::map(BusinessFieldsForm::find()->all(), 'business_field_id', 'name'),
                                'options' => [
                                    'placeholder' => 'Lĩnh vực kinh doanh...',
                                    'disabled' => true,
                                ],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                    'width' => '100%',
                                ],
                            ])->label('Lĩnh vực kinh doanh') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tài khoản người dùng -->
        <div class="col-md-6 d-flex">
            <div class="card flex-fill shadow-sm border-0 d-flex flex-column">
                <div class="card-header-account fw-bold">Tài khoản người dùng</div>
                <div class="card-body flex-fill d-flex flex-column">
                    <div class="row g-3">
                        <div class="col-12">
                            <label>Username</label>
                            <div class="input-group">
                                <span class="input-group-text">👤</span>
                                <input type="text" class="form-control" readonly value="<?= isset($model->user) ? $model->user->username : '' ?>">
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Password</label>
                            <div class="input-group">
                                <span class="input-group-text">🔒</span>
                                <input type="password" class="form-control" readonly value="********">
                            </div>
                        </div>
                        <div class="col-12">
                            <label>Xác nhận mật khẩu</label>
                            <div class="input-group">
                                <span class="input-group-text">🔒</span>
                                <input type="password" class="form-control" readonly value="********">
                            </div>
                        </div>
                        <div class="col-12">
                            <?= $form->field($model, 'status')->input('range', [
                                'min' => -1, 'max' => 1, 'step' => 1,
                                'value' => $model->status,
                                'disabled' => true,
                                'id' => 'statusRange'
                            ])->label('Trạng thái') ?>
                            <div class="form-text" id="statusText">
                                <?= $model->status == 1 ? 'Hoạt động' : ($model->status == 0 ? 'Không hoạt động' : 'Ngừng hoạt động') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>

<script>
    $(document).ready(function() {
        // Chỉ hiển thị trạng thái text
        let value = parseInt($('#statusRange').val());
        let text = value === 1 ? 'Hoạt động' : value === 0 ? 'Không hoạt động' : 'Ngừng hoạt động';
        $('#statusText').text(text);
    });
</script>
