<?php
use yii\helpers\Html;
use yii\helpers\Url;

/** @var array $roles */
/** @var array $user_role */
/** @var array $permissions */
/** @var array $routes */
/** @var array $role_permissions */
/** @var array $role_routes */
/** @var string $user */
/** @var int $id */
?>

<style>
.route-indent-1 { margin-left: 20px; }
.route-indent-2 { margin-left: 40px; }
.route-indent-3 { margin-left: 60px; }
.permission-group {
    border: 1px solid #ddd;
    border-radius: 8px;
    background: #f8f9fa;
    margin-bottom: 10px;
    padding: 8px 10px;
}
</style>

<div class="user-permission-container">
    <h5 class="mb-4">
        <i class="fa fa-user-shield me-2 text-primary"></i>
        Phân quyền cho người dùng: <span class="fw-bold text-danger"><?= Html::encode($user) ?></span>
    </h5>

    <div class="row">
        <!-- 🟦 Vai trò -->
        <div class="col-md-4">
            <h6 class="fw-bold mb-2 text-primary">Vai trò (Roles)</h6>

            <?= Html::beginForm(Url::to(['/user_management/user-permission/save-roles']), 'post', ['id' => 'form-save-roles']) ?>

            <?php foreach ($roles as $r): ?>
                <div class="form-check mb-1">
                    <?= Html::checkbox('roles[]',
                        in_array($r['name'], array_column($user_role, 'name')),
                        [
                            'class' => 'form-check-input role-checkbox',
                            'id' => 'role_' . str_replace(['/', '@', '*', '-', '.'], '_', $r['name']),
                            'value' => $r['name'],
                        ]) ?>
                    <label class="form-check-label" for="role_<?= str_replace(['/', '@', '*', '-', '.'], '_', $r['name']) ?>">
                        <?= Html::encode($r['description'] ?: $r['name']) ?>
                    </label>
                </div>
            <?php endforeach; ?>

            <div class="mt-3">
                <?= Html::hiddenInput('user', $user) ?>
                <?= Html::hiddenInput('user_id', $id) ?>
                <?= Html::submitButton('<i class="fa fa-save me-1"></i> Cập nhật vai trò', [
                    'class' => 'btn btn-primary btn-sm w-100',
                    'id' => 'btn-save-roles',
                ]) ?>
            </div>

            <?= Html::endForm() ?>
        </div>

        <!-- 🟩 Quyền -->
        <div class="col-md-4">
            <h6 class="fw-bold mb-2 text-success">Các quyền (Permissions)</h6>
            <div id="permissions-container">
                <?php
                $currentGroup = null;
                foreach ($permissions as $p):
                    if ($currentGroup !== $p['group_name']):
                        if ($currentGroup !== null) echo "</div>";
                        $currentGroup = $p['group_name'];
                        echo "<div class='permission-group'>";
                        echo "<div class='fw-bold text-secondary mb-2'><i class='fa fa-folder-open me-1'></i>{$currentGroup}</div>";
                    endif;

                    $isChecked = in_array($p['name'], $role_permissions);
                ?>
                    <div class="form-check mb-1">
                        <?= Html::checkbox('permissions[]', $isChecked, [
                            'class' => 'form-check-input permission-checkbox',
                            'id' => 'permission_' . str_replace(['/', '@', '*', '-', '.'], '_', $p['name']),
                            'value' => $p['name'],
                        ]) ?>
                        <label class="form-check-label" for="permission_<?= str_replace(['/', '@', '*', '-', '.'], '_', $p['name']) ?>">
                            <?= Html::encode($p['description'] ?: $p['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- 🟨 Routes -->
        <div class="col-md-4">
            <h6 class="fw-bold mb-2 text-info">Các chức năng (Routes)</h6>
            <div id="routes-container">
                <?php foreach ($routes as $r):
                    $parts = explode('/', trim($r['name'], '/'));
                    $level = count($parts) - 1;
                    $indentClass = 'route-indent-' . min($level, 3);
                    $isChecked = in_array($r['name'], $role_routes);
                ?>
                    <div class="form-check mb-1 <?= $indentClass ?>">
                        <?= Html::checkbox('routes[]', $isChecked, [
                            'class' => 'form-check-input route-checkbox',
                            'id' => 'route_' . str_replace(['/', '@', '*', '-', '.'], '_', $r['name']),
                            'value' => $r['name'],
                        ]) ?>
                        <label class="form-check-label" for="route_<?= str_replace(['/', '@', '*', '-', '.'], '_', $r['name']) ?>">
                            <?= Html::encode($r['name']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
$ajaxUrl = Url::to(['/user_management/user/user-permission/ajax-get-by-role']);
$saveUrl1 = Url::to(['/user_management/user/user-permission/save-roles']);

$js = <<<JS
// ================================
// 🟦 Khi thay đổi vai trò
// ================================
$(document).off('change', '.role-checkbox').on('change', '.role-checkbox', function() {
    const selectedRoles = $('.role-checkbox:checked').map(function() {
        return $(this).val();
    }).get();

    console.log('Roles được chọn:', selectedRoles);

    $.ajax({
        url: '$ajaxUrl',
        type: 'GET',
        dataType: 'json',
        data: { roles: selectedRoles },
        beforeSend: function() {
            $('.permission-checkbox, .route-checkbox').prop('disabled', true);
        },
        success: function(res) {
            $('.permission-checkbox, .route-checkbox').prop('disabled', false);

            if (res && res.success) {
                $('.permission-checkbox, .route-checkbox').prop('checked', false);

                res.permissions.forEach(function(p) {
                    const id = 'permission_' + p.replace(/[\/@*.\-]/g, '_');
                    $('#' + id).prop('checked', true);
                });

                res.routes.forEach(function(r) {
                    const id = 'route_' + r.replace(/[\/@*.\-]/g, '_');
                    $('#' + id).prop('checked', true);
                });
            } else {
                Swal.fire({ icon: 'warning', title: 'Không có dữ liệu', text: 'Không tìm thấy quyền cho vai trò đã chọn.' });
            }
        },
        error: function(xhr) {
            $('.permission-checkbox, .route-checkbox').prop('disabled', false);
            Swal.fire({ icon: 'error', title: 'Lỗi', text: 'Không thể tải dữ liệu quyền của vai trò. (' + xhr.status + ')' });
        }
    });
});

// ================================
// 🟩 Lưu vai trò qua AJAX
// ================================
$('#form-save-roles').on('submit', function(e) {
    e.preventDefault();
    const formData = $(this).serialize();

    $.ajax({
        url: '$saveUrl1',
        type: 'POST',
        data: formData,
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon: 'success', title: 'Thành công', text: res.message, timer: 1500, showConfirmButton: false });
            } else {
                Swal.fire({ icon: 'error', title: 'Lỗi', text: res.message || 'Không thể cập nhật vai trò.' });
            }
        },
        error: function() {
            Swal.fire({ icon: 'error', title: 'Lỗi máy chủ', text: 'Không thể gửi dữ liệu lên máy chủ.' });
        }
    });
});
JS;

$this->registerJs($js);
?>
