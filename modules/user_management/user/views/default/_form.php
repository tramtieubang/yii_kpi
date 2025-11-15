<?php

use app\modules\user_management\user\models\UserForm;
use webvimark\modules\UserManagement\UserManagementModule;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\UserForm */
/* @var $form yii\widgets\ActiveForm */
?>

<style>
    .form-label {
        font-weight: 600;
    }
</style>

<div class="user-form-form">

    <?php $form = ActiveForm::begin([
        'layout' => 'horizontal', // quan trọng
        'fieldConfig' => [
            'horizontalCssClasses' => [
                'label' => 'col-sm-3 col-form-label text-end', // label bên trái
                'wrapper' => 'col-sm-9',                        // input bên phải
                'error' => '',
                'hint' => '',
            ],
        ],
    ]); ?>

    <?= $form->field($model->loadDefaultValues(), 'status')
        ->dropDownList(UserForm::getStatusList(), [
            'prompt' => '--- Chọn trạng thái ---',
        ])
        ->label('Trạng thái tài khoản') ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true,'autocomplete'=>'off'])->label('Tên đăng nhập <span class="text-danger">*</span>') ?>
   
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true,'autocomplete'=>'off'])->label('Mật khẩu <span class="text-danger">*</span>') ?>

	<?= $form->field($model, 'repeat_password')->passwordInput(['maxlength' => true,'autocomplete'=>'off'])->label('Nhập lại mật khẩu <span class="text-danger">*</span>') ?>

	<?php if ( UserForm::hasPermission('bindUserToIp') ): ?>

		<?= $form->field($model, 'bind_to_ip')
			->textInput(['maxlength' => 255])
			->hint(UserManagementModule::t('back','For example: 123.34.56.78, 168.111.192.12')) ?>

	<?php endif; ?>

	<?php if ( UserForm::hasPermission('editUserEmail') ): ?>

		<?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
		<?= $form->field($model, 'email_confirmed')->checkbox() ?>

	<?php endif; ?>

	<?php if (!Yii::$app->request->isAjax): ?>
        <div class="form-group row mt-4">
            <div class="col-sm-9 offset-sm-3">
                <?= Html::submitButton(
                    $model->isNewRecord ? 'Thêm mới' : 'Cập nhật',
                    ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
                ) ?>
            </div>
        </div>
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
