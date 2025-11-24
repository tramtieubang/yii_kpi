<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\kpi\models\KpiKpiForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kpi-kpi-form-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row g-3">
		<div class="col-md-4">
			<?= $form->field($model, 'code')->textInput(['maxlength' => true])->label('Mã KPI') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'name')->textInput(['maxlength' => true])->label('Tên KPI') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'unit')->textInput(['maxlength' => true])->label('Đơn vị') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'target')->textInput(['maxlength' => true])->label('Mục tiêu') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'weight')->textInput(['maxlength' => true])->label('Trọng số') ?>
		</div>
		<div class="col-md-4">
			<?= $form->field($model, 'color')->input('color', ['value' => $model->color ?? '#ff0000'])->label('Màu sắc') ?>
		</div>
		<div class="col-md-12">
			<?= $form->field($model, 'description')->textarea(['rows' => 4])->label('Mô tả') ?>
		</div>
	</div>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
