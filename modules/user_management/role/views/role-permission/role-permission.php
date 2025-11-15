<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $permissions */
/** @var string $roleName */
/** @var array $user_role */

$groupedItems = [];
foreach ($permissions as $item) {
    if (!isset($item['group'])) continue;
    $groupedItems[$item['group']][] = $item;
}
?>
<style>
   .btn-icon {
    padding: 2px 4px;            /* nhỏ gọn hơn */
    line-height: 1;
    font-size: 0.75em;           /* giữ icon rõ */
    border-radius: 50%;          /* bo tròn */
    border: 1px solid transparent;
    transition: all 0.2s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 22px;                 /* cố định kích thước để tròn đều */
    height: 22px;
}

.btn-icon i {
    transition: transform 0.2s ease-in-out, color 0.2s;
}

.btn-icon:hover {
    background-color: #f0f0f0;
    border-color: #ccc;
    transform: scale(1.1);
}

.btn-icon:hover i {
    transform: rotate(10deg);
    color: #0d6efd;
}

</style>
<div class="role-permission-form">

    <?php 
        $form = ActiveForm::begin([
            'layout' => 'horizontal',
            'id' => 'role-permission-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,            
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off',
            ],
            'action' => ['/user_management/role/role-permission/save-permissions','id'=>$roleName],
        ]);
    ?>
    <div class="role-permission-container">
        <h5 class="mb-3">Phân quyền cho vai trò: <b><?= Html::encode($roleName) ?></b></h5>

        <?php foreach ($groupedItems as $group => $items): 
            $groupId = md5($group); // id nhóm an toàn
        ?>
            <div class="permission-group border rounded p-3 mb-4 shadow-sm bg-light" data-group-id="<?= $groupId ?>">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Checkbox nhóm -->
                    <div>
                        <input type="checkbox" 
                            class="form-check-input me-2 check-all-group" 
                            data-group="<?= $groupId ?>" 
                            id="checkall-<?= $groupId ?>">
                        <label for="checkall-<?= $groupId ?>" class="form-check-label fw-bold text-success">
                            <?= Html::encode($group) ?>
                        </label>
                    </div>
                    <small class="text-muted"><?= count($items) ?> quyền</small>
                </div>

                <!-- Danh sách quyền con chia 3 cột -->
               <div class="row">
                    <?php foreach ($items as $item): ?>
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="form-check d-flex align-items-center gap-1">
                                <input type="checkbox"
                                    class="form-check-input permission-item"
                                    id="permission_<?= $item['name'] ?>"
                                    data-group="<?= $groupId ?>"
                                    name="permissions[]"
                                    value="<?= Html::encode($item['name']) ?>"
                                    <?= in_array($item['name'], $user_role) ? 'checked' : '' ?>>

                                <!-- <button type="button" 
                                    class="btn btn-light btn-icon btn-role-settings"
                                    title="Cấu hình quyền <?= Html::encode($item['description']) ?>"
                                    data-role="<?= Html::encode($item['name']) ?>">
                                    <i class="fa fa-cog"></i>
                                </button>  -->

                               <!--  <?= Html::a(
                                    '<i class="fa fa-cog"></i>',
                                    ['/permission/permission-route/', 'id' => $item['name']],
                                    [
                                        'title' => 'Cấu hình quyền ' . Html::encode($item['description']),
                                        'class' => 'btn btn-light btn-icon btn-role-settings ripple btn-sm',
                                        'role' => 'modal-remote', // nếu bạn dùng modal AJAX
                                        'data-pjax' => 0,
                                        'data-target' => '#ajaxCrudModal2', // 🔹 chỉ định modal thứ 2
                                        'data-bs-toggle' => 'tooltip',
                                        'data-bs-placement' => 'top',
                                    ]
                                ) ?> -->

                                <?= Html::button('<i class="fa fa-cog"></i>', [
                                    'title' => 'Cập nhật chức năng cho ' . Html::encode($item['description']),
                                    'class' => 'btn btn-light btn-icon btn-role-settings ripple btn-sm',
                                    'role' => 'modal-remote',
                                    'data-pjax' => 0,
                                    'role' => 'modal-remote-3',      // để ajaxcrud nhận dạng  👈 DÙNG role riêng để phân biệt modal 2
                                    'data-url' => Url::to(['/user_management/permission/permission-route', 'id' => $item['name']]), // 🔹 URL AJAX
                                    'data-bs-toggle' => 'tooltip',
                                    'data-bs-placement' => 'top',
                                ]) ?>
                                
                                <label class="form-check-label mb-0 flex-grow-1"
                                    for="permission_<?= $item['name'] ?>">
                                    <?= Html::encode($item['description']) ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

            </div>
        <?php endforeach; ?>
    </div>
    <?php ActiveForm::end(); ?>
    
</div>
<?php
$script = <<<'JS'


function permissionItemsByGroup(groupId) {
    return $('.permission-item').filter(function() {
        return String($(this).data('group')) === String(groupId);
    });
}

// Tick "Chọn tất cả" trong nhóm
$(document).on('change', '.check-all-group', function() {
    var groupId = $(this).data('group');
    var checked = $(this).is(':checked');
    permissionItemsByGroup(groupId).prop('checked', checked);
});

// Bỏ/Check checkbox con -> cập nhật checkbox nhóm
$(document).on('change', '.permission-item', function() {
    var groupId = $(this).data('group');
    var items = permissionItemsByGroup(groupId);
    var total = items.length;
    var checked = items.filter(':checked').length;
    $('.check-all-group').filter(function() {
        return String($(this).data('group')) === String(groupId);
    }).prop('checked', total > 0 && total === checked);
});

// Khởi tạo trạng thái check-all khi load trang
function initCheckAllStatus() {
    $('.check-all-group').each(function() {
        var groupId = $(this).data('group');
        var items = permissionItemsByGroup(groupId);
        var total = items.length;
        var checked = items.filter(':checked').length;
        $(this).prop('checked', total > 0 && total === checked);
    });
}


$(function() {
    initCheckAllStatus();
});
JS;

$this->registerJs($script);

?>
