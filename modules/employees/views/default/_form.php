<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\employees\models\EmployeesForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="employees-form-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">
<div class="col-md-4">    <?= $form->field($model, 'user_id')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'department_id')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'position')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'hire_date')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'created_at')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'updated_at')->textInput() ?>

</div>  
	</div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
