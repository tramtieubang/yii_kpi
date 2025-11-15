<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var array $routes */
/** @var string $roleName */
/** @var array $role_permissoin */

// Tạo cây phân cấp từ routes
function buildTree(array $routes) {
    $tree = [];
    foreach ($routes as $item) {
        $parts = explode('/', $item['name']);
        $ref = &$tree;
        foreach ($parts as $part) {
            if (!isset($ref[$part])) {
                $ref[$part] = [];
            }
            $ref = &$ref[$part];
        }
        $ref['_name'] = $item['name'];         // lưu name đầy đủ
        $ref['_description'] = $item['name'];  // dùng name hiển thị
    }
    return $tree;
}

// Hiển thị cây
function renderTree(array $tree, array $role_permissoin) {
    echo '<ul>';
    foreach ($tree as $key => $node) {
        if (in_array($key, ['_name','_description'])) continue;

        $hasChildren = count(array_filter(array_keys($node), fn($k)=>!in_array($k, ['_name','_description']))) > 0;
        $name = $node['_name'] ?? $key;
        $desc = $node['_description'] ?? $key; // hiển thị full name
        $checked = isset($node['_name']) && in_array($node['_name'], $role_permissoin) ? 'checked' : '';
        ?>
        <li>
            <div class="form-check">
                <input type="checkbox" class="form-check-input permission-item" data-toggle="tree" name="routes[]" value="<?= Html::encode($name) ?>" <?= $checked ?>>
                <label class="form-check-label"><?= Html::encode($desc) ?></label>
            </div>
            <?php if ($hasChildren): ?>
                <?php renderTree($node, $role_permissoin); ?>
            <?php endif; ?>
        </li>
        <?php
    }
    echo '</ul>';
}

$tree = buildTree($routes);
?>

<div class="permission-route-form">

    <?php 
        $form = ActiveForm::begin([
            'id' => 'permission-route-form',
            'enableAjaxValidation' => false,
            'enableClientValidation' => true,            
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off', 
            ],
            'action' => ['/user_management/permission/permission-route/save-routes','id'=>$roleName],
        ]);
    ?>

    <!--  <h5 class="mb-3">Cài đặt quyền hạn: <b><?= Html::encode($roleName) ?></b></h5> -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <!-- Bên trái: Tên role -->
        <h5 class="mb-0">Cài đặt quyền hạn: <b><?= Html::encode($roleName) ?></b></h5>

        <!-- Bên phải: Button thao tác -->
       <div class="btn-group">
            <!-- Button Làm mới tuyến đường -->
            <?= Html::a('<i class="fa fa-sync-alt me-1"></i> Làm mới tuyến đường', 
                ['/user_management/permission/permission-route/refresh-routes', 'id' => $roleName], 
                [
                    'class' => 'btn btn-sm btn-primary',
                    'data-pjax' => 0,
                    'role' => 'modal-remote', // nếu dùng AJAX modal
                    'data-bs-toggle' => 'tooltip',
                    'title' => 'Cập nhật lại tất cả route mới',
                ]) 
            ?>

            <!-- Button Xóa tuyến không dùng -->
            <?= Html::a('<i class="fa fa-trash-alt me-1"></i> Xóa tuyến không dùng', 
                ['/user_management/permission/permission-route/refresh-routes', 'id' => $roleName,'deleteUnused' => 1], 
                [
                   /*  'data-params' => [
                        'id' => $roleName,
                        'deleteUnused' => 1
                    ], */
                    'class' => 'btn btn-sm btn-danger',
                    //'data-confirm' => 'Bạn có chắc chắn muốn xóa các route không dùng?',
                    'data-pjax' => 0,
                    'role' => 'modal-remote', // Nếu muốn dùng modal AJAX, bật 
                    // nhưng thông thường xóa route dùng post thông thường
                    'title' => 'Làm mới tất cả route và cập nhật lại các route không dùng',
                    'onclick' => "return confirm('Bạn có chắc chắn muốn xóa các route không dùng?');" 
                ]) 
            ?>
        </div>

    </div>
 

    <div class="permission-route-container">
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <?php renderTree($tree, $role_permissoin); ?>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>

<?php
$css = <<<CSS
.permission-route-container ul {
    list-style: none;
    padding-left: 20px;
}
.permission-route-container ul ul {
    padding-left: 20px;
    border-left: 1px dashed #ccc;
    margin-left: 10px;
}
.permission-route-container li {
    margin-bottom: 4px;
}
CSS;

$this->registerCss($css);

$js = <<<JS
// Tick/uncheck tất cả con khi tick/uncheck cha
$(document).on('change', '.permission-item', function() {
    var checked = $(this).is(':checked');
    $(this).closest('li').find('ul li .permission-item').prop('checked', checked);

    // Cập nhật trạng thái cha
    function updateParent(li) {
        var parentUl = li.parent();
        var allChecked = parentUl.find('> li > .form-check > .permission-item').length ===
                         parentUl.find('> li > .form-check > .permission-item:checked').length;
        var parentCheckbox = parentUl.closest('li').find('> .form-check > .permission-item');
        if(parentCheckbox.length){
            parentCheckbox.prop('checked', allChecked);
            updateParent(parentCheckbox.closest('li'));
        }
    }
    updateParent($(this).closest('li'));
});
JS;

$this->registerJs($js);
?>
