<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var array $permissions */
/** @var array|string[] $group_permission */
/** @var string $id */
/** @var string $name */

// ✅ Nếu $group_permission là model, lấy thuộc tính permissions
if (is_object($group_permission) && property_exists($group_permission, 'permissions')) {
    $checkedPermissions = $group_permission->permissions;
} else {
    $checkedPermissions = (array)$group_permission;
}
?>

<div class="group-add-permission-form">
    <?php 
    $form = ActiveForm::begin([
        'id' => 'group-add-permission-form',
        'layout' => 'horizontal',
        'enableAjaxValidation' => false,
        'enableClientValidation' => true,
        'options' => [
            'data-pjax' => true,
            'autocomplete' => 'off',
        ],
        // ✅ Đường dẫn lưu quyền vào nhóm
        'action' => ['/user_management/permission_group/group-add-permission/save-group-add-permissions', 'id' => $id],
    ]);
    ?>

    <h5 class="mb-3">
        <i class="fa fa-lock text-primary me-1"></i>
        Thêm quyền vào nhóm: <b class="text-danger"><?= Html::encode($name) ?></b>
    </h5>

    <div class="border rounded p-3 bg-light shadow-sm mb-4">
        <div class="form-check mb-2">
            <input type="checkbox" class="form-check-input" id="check-all-permissions">
            <label class="form-check-label fw-bold text-success" for="check-all-permissions">
                Chọn tất cả / Bỏ chọn tất cả
            </label>
        </div>
        <hr>

        <div class="row">
            <?php foreach ($permissions as $p): ?>
                <div class="col-md-4 col-sm-6 mb-2">
                    <div class="form-check">
                        <input type="checkbox"
                            class="form-check-input permission-item"
                            name="permissions[]"
                            value="<?= Html::encode($p['name']) ?>"
                            id="perm_<?= str_replace(['/', '@', '*', '-', '.'], '_', $p['name']) ?>"
                            <?= in_array($p['name'], $checkedPermissions) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="perm_<?= str_replace(['/', '@', '*', '-', '.'], '_', $p['name']) ?>">
                            <?= Html::encode($p['description'] ?: $p['name']) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>

<?php
$script = <<<JS
// ✅ Tick / bỏ chọn tất cả quyền
$(document).on('change', '#check-all-permissions', function() {
    const checked = $(this).is(':checked');
    $('.permission-item').prop('checked', checked);
});

// ✅ Cập nhật trạng thái "Chọn tất cả"
$(document).on('change', '.permission-item', function() {
    const total = $('.permission-item').length;
    const checked = $('.permission-item:checked').length;
    $('#check-all-permissions').prop('checked', total > 0 && total === checked);
});

// ✅ Khi gửi form, thực hiện lưu bằng AJAX
$(document).on('click', '#btn-save-group-add-permission', function(e) {
//$(document).on('submit', '#group-add-permission-form', function(e) {
    e.preventDefault();
    //const form = $(this);
    //const btn = $('#btn-save-group-add-permission');

    const form = $('#group-add-permission-form');
    const btn = $(this);

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang lưu...');
//alert("fasd");
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công',
                    text: res.message || 'Cập nhật quyền thành công!',
                    timer: 1500,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: res.message || 'Không thể lưu thay đổi.'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Lỗi hệ thống',
                text: 'Không thể kết nối đến máy chủ.'
            });
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Lưu thay đổi');
        }
    });
});

// ✅ Khởi tạo trạng thái ban đầu
$(function() {
    const total = $('.permission-item').length;
    const checked = $('.permission-item:checked').length;
    $('#check-all-permissions').prop('checked', total > 0 && total === checked);
});
JS;

$this->registerJs($script);
?>
