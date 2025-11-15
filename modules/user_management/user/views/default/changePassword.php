<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

?>

<div class="change-password-form">

    <?php 
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'change-password-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'label' => 'col-sm-3 col-form-label text-end',
                    'wrapper' => 'col-sm-9',
                ],
            ],
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off',
            ],
            //'action' => ['/user/default/save-change-password'],
            //'action' => ['/user/default/save-change-password','id'=>$model->id],
            'action' => \yii\helpers\Url::to(['/user_management/user/default/save-change-password', 'id' => $model->id]),
            'method' => 'post',
        ]);
    ?>
	
	<div class="row">
        <div class="col-md-12">    
            <div class="row mb-3">
                <div class="col-12 text-center">
                    <strong style="font-size:1.5rem;">
                        Đổi mật khẩu cho người dùng: 
                        <!-- <?= Html::encode(Yii::$app->user->identity->username) ?> -->
                        <?= Html::encode($model->username) ?>
                    </strong>
                </div>
            </div>

            <?= $form->field($model, 'password')
                ->passwordInput([
                    'maxlength' => true,
                    'autocomplete' => 'off',
                    'id' => 'password'
                ])
                ->label('Mật khẩu <span class="text-danger">*</span>') ?>

	        <?= $form->field($model, 'repeat_password')
                ->passwordInput([
                    'maxlength' => true,
                    'autocomplete' => 'off',
                    'id' => 'repeat_password'
                ])
                ->label('Nhập lại mật khẩu <span class="text-danger">*</span>') ?>
        </div>
    </div>
                    
    <?php ActiveForm::end(); ?>
    
</div>

<!-- 🔄 Loading Overlay -->
<div id="loading-overlay" style="
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(255,255,255,0.7);
    z-index:2000;
    text-align:center;
    padding-top:20%;
">
    <div class="spinner-border text-primary" style="width:3rem; height:3rem;" role="status"></div>
    <div style="margin-top:10px; font-weight:600; color:#333;">Đang lưu dữ liệu...</div>
</div>

<script>
$(document).ready(function(){

    // Bắt sự kiện submit form
    $(document).on('submit', '#change-password-form', function(e){
        e.preventDefault(); // Ngăn form reload trang

        const form = $(this);
        const password = $('#password').val().trim();
        const repeatPassword = $('#repeat_password').val().trim();

        // Kiểm tra dữ liệu
        if (!password || !repeatPassword) {
            Swal.fire({
                icon: 'warning',
                title: 'Thiếu dữ liệu!',
                text: 'Vui lòng nhập đầy đủ mật khẩu và nhập lại mật khẩu.',
                toast: true,
                position: 'top-end',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        if (password !== repeatPassword) {
            Swal.fire({
                icon: 'warning',
                title: 'Sai dữ liệu!',
                text: 'Mật khẩu và nhập lại mật khẩu không khớp.',
                toast: true,
                position: 'top-end',
                timer: 2500,
                showConfirmButton: false
            });
            return;
        }

        // Hiển thị loading
        $('#loading-overlay').fadeIn(200);

        //  Gửi AJAX request
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response){
                //alert(response);
                $('#loading-overlay').fadeOut(200);

                if (response.success) {
                    form.trigger('reset');

                    Swal.fire({
                        icon: 'success',
                        title: response.message || 'Đổi mật khẩu thành công!',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true
                    });

                    // Tự đóng modal sau 1.5s
                    setTimeout(function(){
                        const modalEl = document.getElementById('ajaxCrudModal');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            if (modal) modal.hide();
                        }
                    }, 1500);
                } else {
                    Swal.fire('Lỗi!', response.message || 'Không thể kết nối máy chủ.', 'error');
                }
            },
            error: function(){
                $('#loading-overlay').fadeOut(200);
                Swal.fire('Lỗi mạng!', 'Không thể lưu dữ liệu.', 'error');
            }
        });
    });

    // Nếu nút "Lưu" nằm ngoài form
    $(document).on('click', '#btn-change-password', function() {
        $('#change-password-form').trigger('submit');
    });

});
</script>
