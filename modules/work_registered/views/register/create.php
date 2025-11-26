<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use codenixsv\flatpickr\Flatpickr;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkRegisteredForm */
?>

<div class="register-create">

    <?php $form = ActiveForm::begin([
        'id' => 'form-register-create',
        'action' => ['register/create'],
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
            <?= $form->field($model, 'start_date')->widget(Flatpickr::class, [
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
            <?= $form->field($model, 'end_date')->widget(Flatpickr::class, [
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

    <!-- Slider Duyệt / Chưa duyệt -->
   <div class="col-12">
        <label for="statusRange" class="fw-bold">Trạng thái</label>
        <div class="input-group">
            <input type="range" class="form-range" min="1" max="3" step="1" value="2" name="status" id="statusRange">
        </div>
        <div class="form-text" id="statusText">Đã duyệt</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = <<<JS
$(document).on('click', '#btn-create-register', function(e){
    e.preventDefault();

    var form = $('#form-register-create');
    var formData = form.serialize();

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: formData,
        success: function(data){
            // Reload PJAX nếu có container
            if(data.forceReload){
                if($.pjax){
                    $.pjax.reload({container: data.forceReload});
                } else {
                    // fallback reload trang nếu PJAX undefined
                    location.reload();
                }
            }

            // Cập nhật modal nếu server trả content
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
            console.error('AJAX error:', err);
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