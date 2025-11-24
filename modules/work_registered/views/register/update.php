<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* $model->date_start = $model->date_start ? date('Y-m-d H:i:s', strtotime($model->date_start)) : null;
$model->date_end   = $model->date_end   ? date('Y-m-d H:i:s', strtotime($model->date_end)) : null;
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
            <?= $form->field($model, 'date_start')->input('datetime-local', [
                'value' => $model->date_start ? date('Y-m-d\TH:i', strtotime($model->date_start)) : ''
            ]) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'date_end')->input('datetime-local', [
                'value' => $model->date_end ? date('Y-m-d\TH:i', strtotime($model->date_end)) : ''
            ]) ?>
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

