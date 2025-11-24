<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkRegisteredForm */

// Chuyển định dạng ngày sang Flatpickr
//$model->date_start = $model->date_start ? date('d/m/Y H:i:s', strtotime($model->date_start)) : null;
//$model->date_end   = $model->date_end   ? date('d/m/Y H:i:s', strtotime($model->date_end))   : null;

?>

<div class="register-create">

    <?php $form = ActiveForm::begin([
        'id' => 'form-register',
        'action' => ['create'],
        'options' => ['data-pjax' => true],
    ]); ?>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-12">
            <?= $form->field($model, 'description')->textarea(['rows' => 4]) ?>
        </div>
            <div class="col-md-6">
            <?= $form->field($model, 'date_start')->widget(codenixsv\flatpickr\Flatpickr::class, [
                'clientOptions' => [
                    'enableTime'    => true,
                    'enableSeconds' => true,
                    'dateFormat'    => 'd/m/Y H:i:s',
                    'time_24hr'     => true,
                    'locale'        => 'vn',
                ],
                'options' => ['class' => 'form-control flatpickr-input'],
            ]); ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date_end')->widget(codenixsv\flatpickr\Flatpickr::class, [
                'clientOptions' => [
                    'enableTime'    => true,
                    'enableSeconds' => true,
                    'dateFormat'    => 'd/m/Y H:i:s',
                    'time_24hr'     => true,
                    'locale'        => 'vn',
                ],
                'options' => ['class' => 'form-control flatpickr-input'],
            ]); ?>
        </div>    
    </div>

    <?php ActiveForm::end(); ?>

</div>
