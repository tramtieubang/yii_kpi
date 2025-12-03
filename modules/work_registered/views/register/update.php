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
        <label for="statusRange" class="fw-bold">Trạng thái</label>
        <div class="input-group">
            <input type="range" class="form-range" min="1" max="3" step="1"
                value="<?= $model->status_id ?>" name="status" id="statusRange">
        </div>
        <div class="form-text" id="statusText">
            <?= $model->status ? $model->status->name : '' ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php

$js = <<<JS
$(document).on('click', '#btn-update-register', function(e){
    e.preventDefault();

    var form = $('#form-register-update');

    $.ajax({
        url: form.attr('action'),
        type: form.attr('method') || 'post',
        data: form.serialize(),
        dataType: 'json',
        success: function(data){
            // 🔔 Hiện thông báo thành công (nếu có)
            // ❗ Nếu server trả về lỗi (ví dụ: đã có báo cáo)
            // ❌ Trường hợp báo lỗi
            if(data.success === false){
                Swal.fire({
                    icon: 'error',
                    title: 'Không thể sửa!',
                    text: data.message,
                    confirmButtonText: 'Đóng'
                });
                return;
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

            // ⏱ Chờ 1.2 giây trước khi đóng modal
            setTimeout(function(){
                // 🔹 Đóng modal nếu có flag
                if(data.closeModal){
                    $('#ajaxCrudModal').modal('hide');
                }

                // 🔹 Reload PJAX nếu có container
                if(data.forceReload){
                    if($.pjax && $(data.forceReload).length > 0){
                        $.pjax.reload({container: data.forceReload});
                    } else {
                        location.reload(); // fallback reload toàn trang
                    }
                }

                // 🔹 Reload FullCalendar nếu cần
                if(data.refreshCalendar && window.calendarInstance){
                    window.calendarInstance.refetchEvents();
                }
            }, 1200); // 1.2 giây
        },
        error: function(err){
            console.log(err);
            alert('Có lỗi xảy ra!');
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

    // Gán giá trị cho input status trong ActiveForm
    $('#form-register-update').find('[name="Register[status]"]').val(value);
});
</script>
