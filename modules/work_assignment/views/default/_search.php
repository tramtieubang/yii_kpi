<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\work_assignment\models\KpiWorkAssignmentForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="kpi-work-assignment-form-search">

    <?php $form = ActiveForm::begin([
        	'id'=>'myFilterForm',
            'method' => 'get',
            'options' => [
                'class' => 'myFilterForm'
            ]
      	]); ?>
	<div class="row">
<div class="col-md-4">    <?= $form->field($model, 'work_registered_id')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'staff_id')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'status_id')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'start_date')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'end_date')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'assigned_at')->textInput() ?>

</div>  
	</div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton('Tìm kiếm',['class' => 'btn btn-primary']) ?>
	        <?= Html::button('Xóa tìm kiếm', ['class' => 'btn btn-outline-secondary', 'onclick' => 'resetSearchForm()']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>

<script>
	function resetSearchForm() {
		const form = $('#myFilterForm');
		form.find('input[type="text"], select').val('');
		form.submit();
	}
</script>