<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;
?>

<?php if (
	PermissionHelper::check('work-registered/register/index') ||
	PermissionHelper::check('employees/default/index') ||
	PermissionHelper::check('kpi/default/index') 
): ?>
    <li class="slide">
        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
            <span class="side-menu__label"><i class="fe fe-briefcase"></i> Quản lý công việc </span><i class="angle bi bi-caret-right"></i>
        </a>

        <ul class="slide-menu" data-menu="cv">
            <li class="panel sidetab-menu">
                <div class="panel-body tabs-menu-body p-0 border-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="side7">
                            <ul class="sidemenu-list">
                                <?php if (PermissionHelper::check('work-registered/register')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-clipboard"></i> Đăng ký công việc', ['/work-registered/register', 'menu'=>'cv1'], ['class' => 'slide-item', 'data-menu' => 'cv1']) ?>
                                    </li>
                                <?php endif; ?>                               
                                <?php if (PermissionHelper::check('employees/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-thumbs-up"></i> Phê duyệt đăng ký', ['/employees/default', 'menu'=>'cv4'], ['class' => 'slide-item', 'data-menu' => 'cv4']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (PermissionHelper::check('kpi/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-share-2"></i> Phân công công việc', ['/kpi/default', 'menu'=>'cv5'], ['class' => 'slide-item', 'data-menu' => 'cv5']) ?>
                                    </li>
                                <?php endif; ?>
                                 <?php if (PermissionHelper::check('kpi/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-bar-chart"></i> Báo cáo công việc', ['/kpi/default', 'menu'=>'cv6'], ['class' => 'slide-item', 'data-menu' => 'cv6']) ?>
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