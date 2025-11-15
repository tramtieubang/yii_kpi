<?php
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\permission_group\models\PermissionGroupForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="permission-group-form-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<div class="row">
<div class="col-md-4">    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

</div><div class="col-md-8">    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

</div>  
	</div>
	<?php if (!Yii::$app->request->isAjax){ ?>
	  	<div class="form-group">
	        <?= Html::submitButton($model->isNewRecord ? 'Thêm mới' : 'Cập nhật', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	    </div>
	<?php } ?>

    <?php ActiveForm::end(); ?>
    
</div>
