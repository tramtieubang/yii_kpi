<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* $model->start_date = $model->start_date ? date('Y-m-d H:i:s', strtotime($model->start_date)) : null;
$model->end_date   = $model->end_date   ? date('Y-m-d H:i:s', strtotime($model->end_date)) : null;
 */
?>

<div class="register-update">

    <?php $form = ActiveForm::begin([
        'id' => 'form-register-update',
        'action' => ['register/update', 'id' => $model->id],
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
            <?= $form->field($model, 'start_date')->input('datetime-local', [
                'value' => $model->start_date ? date('Y-m-d\TH:i', strtotime($model->start_date)) : ''
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'end_date')->input('datetime-local', [
                'value' => $model->end_date ? date('Y-m-d\TH:i', strtotime($model->end_date)) : ''
            ]) ?>
        </div>
    </div>

     <!-- Slider Duyệt / Chưa duyệt -->
    <div class="col-12">
        <?= $form->field($model, 'status', [
                'template' => "{label}\n<div class='input-group'>
                    <input type='range' class='form-range' min='1' max='3' step='1' value='2' id='statusRange'>
                    {input}
                </div>\n{error}"
            ])->label('Trạng thái', ['class' => 'fw-bold']) ?>
        <div class="form-text" id="statusText">
            <?php 
                echo 'Đã duyệt'; 
            ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$js = <<<JS
// Bắt sự kiện click cho các nút có id btn-update-register trong modal, kể cả được render sau
$(document).on('click', '#btn-update-register', function(e){
    e.preventDefault();
    var form = $('#form-register-update');
    //alert("test");
    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: form.serialize(),
        success: function(data){
            //alert("data");
            if(data.forceReload){
                $.pjax.reload({container: data.forceReload});
            }
            if(data.content){
                $('#ajaxCrudModal .modal-body').html(data.content);
            }
            if(data.footer){
                $('#ajaxCrudModal .modal-footer').html(data.footer);
            }
            if(data.title){
                $('#ajaxCrudModal .modal-title').html(data.title);
            }
        },
        error: function(err){
            console.log(err);
        }
    });
});
JS;
$this->registerJs($js);
?>


<script>
    $(document).off('input', '#statusRange').on('input', '#statusRange', function() {
		let value = parseInt(this.value);
		let text = '';
		switch(value){
			case 1: text = 'Chưa duyệt'; break;
			case 2: text = 'Đã duyệt'; break;
			case 3: text = 'Từ chối'; break;
		}
		$('#statusText').text(text);
		$('#staffform-status').val(value);
	}); 
</script>