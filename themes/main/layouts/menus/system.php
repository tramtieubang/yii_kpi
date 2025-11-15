<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;
?>

<?php if (
	PermissionHelper::check('user_management/user/default/index') ||
	PermissionHelper::check('user_management/role/default/index') ||
    PermissionHelper::check('user_management/permission/default/index') ||
	PermissionHelper::check('user_management/permission_group/default/index') ||
    PermissionHelper::check('user_management/session_manager/default/index') 
): ?>
    <li class="slide">
        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
            <span class="side-menu__label"><i class="fe fe-settings"></i> Hệ thống </span><i class="angle bi bi-caret-right"></i>
        </a>

        <ul class="slide-menu" data-menu="st">
            <li class="panel sidetab-menu">
                <div class="panel-body tabs-menu-body p-0 border-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="side7">
                            <ul class="sidemenu-list">
                                <?php if (PermissionHelper::check('/user_management/user/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-user"></i> Quản lý người dùng', ['/user_management/user/default', 'menu'=>'st1'], ['class' => 'slide-item', 'data-menu' => 'st1']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (PermissionHelper::check('/user_management/role/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-users"></i> Vai trò', ['/user_management/role/default', 'menu'=>'st3'], ['class' => 'slide-item', 'data-menu' => 'st3']) ?>
                                    </li>
                                <?php endif; ?> 
                                <?php if (PermissionHelper::check('/user_management/permission/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-shield"></i> Quyền hạn', ['/user_management/permission/default', 'menu'=>'st2'], ['class' => 'slide-item', 'data-menu' => 'st2']) ?>
                                    </li>
                                <?php endif; ?>                                 
                                <?php if (PermissionHelper::check('/user_management/permission_group/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-layers"></i> Nhóm quyền', ['/user_management/permission_group/default', 'menu'=>'st4'], ['class' => 'slide-item', 'data-menu' => 'st4']) ?>
                                    </li>
                                <?php endif; ?>  
                                 <?php if (PermissionHelper::check('/user_management/session_manager/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-lock"></i> Quản lý phiên đăng nhập', ['/user_management/session_manager/default', 'menu'=>'st5'], ['class' => 'slide-item', 'data-menu' => 'st5']) ?>
                                    </li>
                                <?php endif; ?>          
                              
                            </ul>
                            <div class="menutabs-content px-0">
                                <!-- menu tab here -->
                            </div>
                        </div>
                        <div class="tab-pane" id="side8">
                            <!-- activity here -->
                        </div>
                    </div>
                </div>
            </li>
        </ul>
    </li>
<?php endif; ?>