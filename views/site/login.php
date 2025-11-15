<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use app\assets\ViboonAsset;

ViboonAsset::register($this);

$this->title = 'Đăng nhập';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="card shadow-lg border-1 rounded-4">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4 fw-bold fs-2"><?= Html::encode($this->title) ?></h3>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n<div class='input-group'>{input}{addon}</div>\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <!-- Username -->
                <div class="mb-3">
                    <?= $form->field($model, 'username', [
                        'parts' => [
                            '{addon}' => '<span class="input-group-text"><i class="bi bi-person"></i></span>',
                        ]
                    ])->textInput([
                        'autofocus' => true,
                        'placeholder' => 'Nhập tên đăng nhập',
                        'class' => 'form-control'
                    ])->label('Tên đăng nhập') ?>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <?= $form->field($model, 'password', [
                        'parts' => [
                            '{addon}' => '
                                <span class="input-group-text password-toggle" style="cursor:pointer;">
                                    <i class="bi bi-eye" id="togglePassword"></i>
                                </span>
                            ',
                        ]
                    ])->passwordInput([
                        'placeholder' => 'Nhập mật khẩu',
                        'class' => 'form-control',
                        'id' => 'password-field'
                    ]) ?>
                </div>

                <!-- Remember Me -->
                <div class="form-check mb-3">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => "{input} {label}\n{error}",
                        'class' => 'form-check-input',
                        'labelOptions' => ['class' => 'form-check-label'],
                    ])->label('Ghi nhớ đăng nhập') ?>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton('Đăng nhập', ['class' => 'btn btn-primary btn-lg']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- Script toggle password -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const togglePassword = document.getElementById('togglePassword');
    const passwordField  = document.getElementById('password-field');

    togglePassword.addEventListener('click', function () {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);

        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
});
</script>
