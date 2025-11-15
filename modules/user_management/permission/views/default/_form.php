<?php

use app\modules\user_management\permission_group\models\PermissionGroupForm;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\permission\models\PermissionForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-form-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">
<div class="col-md-12">    
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-12">   
	<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-12">    
	<?= //$form->field($model, 'group_code')->textInput(['maxlength' => true]) 
		$form->field($model, 'group_code')->widget(Select2::classname(), [
			'data' => \yii\helpers\ArrayHelper::map(
				PermissionGroupForm::find()->all(), 
				'code', 
				function($model) {
					return '<div style="display: flex;">
								<div style="width: 35%;">'.$model->code.'</div> 
								<div>'.$model->name.'</div>
							</div>';
				}
			),
			'options' => [
				'placeholder' => 'Chọn nhóm quyền...',
			],
			'pluginOptions' => [
				'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
				'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
				'allowClear' => true,
			],
		]);
	
	?>

</div>  
	</div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
