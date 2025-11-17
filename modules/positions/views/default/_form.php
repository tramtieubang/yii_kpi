<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\positions\models\PositionsForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="positions-form-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row g-3">		
		<div class="col-md-12">    
			<?= $form->field($model, 'name', [
					'labelOptions' => ['class' => 'form-label fw-bold'],
					'template' => "{label}\n{input}\n{error}",
				])->textInput(['maxlength' => true])
				->label($model->getAttributeLabel('name') . ' <span class="text-danger">*</span>') ?>
		</div>
		<div class="col-md-12">    
			<?= $form->field($model, 'description', [
					'labelOptions' => ['class' => 'form-label fw-bold'],
					'template' => "{label}\n{input}\n{error}",
				])->textarea(['rows' => 4, 'class' => 'form-control'])
				->label($model->getAttributeLabel('description')) ?>

		</div>  
	</div>

	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
