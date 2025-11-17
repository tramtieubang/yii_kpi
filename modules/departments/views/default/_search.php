<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\departments\models\DepartmentsForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form-search">

    <?php $form = ActiveForm::begin([
        	'id'=>'myFilterForm',
            'method' => 'get',
            'options' => [
                'class' => 'myFilterForm'
            ]
      	]); ?>
	<div class="row">
<div class="col-md-4">    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

</div><div class="col-md-4">    <?= $form->field($model, 'created_at')->textInput() ?>

</div><div class="col-md-4">    <?= $form->field($model, 'updated_at')->textInput() ?>

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