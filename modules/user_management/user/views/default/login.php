<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
//use yii\bootstrap5\Html;
use app\assets\ViboonAsset;
use yii\helpers\Html;

ViboonAsset::register($this);

$this->title = 'Đăng nhập';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login d-flex align-items-center justify-content-center min-vh-100">
    <div class="col-md-8 col-lg-6 col-xl-4">
        <div class="card shadow-lg border-0 rounded-3">
            <div class="card-body p-4">
                <h3 class="card-title text-center mb-4"><?= Html::encode($this->title) ?></h3>

                <?php $form = ActiveForm::begin([
                    'id' => 'login-form',
                    'fieldConfig' => [
                        'template' => "{label}\n{input}\n{error}",
                        'labelOptions' => ['class' => 'form-label'],
                        'inputOptions' => ['class' => 'form-control'],
                        'errorOptions' => ['class' => 'invalid-feedback d-block'],
                    ],
                ]); ?>

                <div class="mb-3">
                    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                </div>

                <div class="mb-3">
                    <?= $form->field($model, 'password')->passwordInput() ?>
                </div>

                <div class="form-check mb-3">
                    <?= $form->field($model, 'rememberMe')->checkbox([
                        'template' => "{input} {label}\n{error}",
                        'class' => 'form-check-input',
                        'labelOptions' => ['class' => 'form-check-label'],
                    ]) ?>
                </div>

                <div class="d-grid">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
