<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;
?>

<?php if (
	PermissionHelper::check('deparments/default/index') ||
	PermissionHelper::check('employees/default/index') ||
	PermissionHelper::check('kpi/default/index') 
): ?>
    <li class="slide">
        <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);">
            <span class="side-menu__label"><i class="fe fe-pie-chart"></i> Báo cáo – Thống kê </span><i class="angle bi bi-caret-right"></i>
        </a>

        <ul class="slide-menu" data-menu="bc">
            <li class="panel sidetab-menu">
                <div class="panel-body tabs-menu-body p-0 border-0">
                    <div class="tab-content">
                        <div class="tab-pane active" id="side7">
                            <ul class="sidemenu-list">
                                <?php if (PermissionHelper::check('deparments/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-bar-chart-2"></i> Báo cáo tổng hợp KPI ', ['/deparments/default', 'menu'=>'bc1'], ['class' => 'slide-item', 'data-menu' => 'bc2']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (PermissionHelper::check('employees/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-activity"></i> Báo cáo tiến độ công việc ', ['/employees/default', 'menu'=>'bc2'], ['class' => 'slide-item', 'data-menu' => 'bc2']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (PermissionHelper::check('kpi/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-users"></i> Báo cáo so sánh nhân viên ', ['/kpi/default', 'menu'=>'bc3'], ['class' => 'slide-item', 'data-menu' => 'bc3']) ?>
                                    </li>
                                <?php endif; ?>
                                <?php if (PermissionHelper::check('kpi/default')): ?>
                                    <li>
                                        <?= Html::a('<i class="fe fe-grid"></i> Báo cáo so sánh phòng ban ', ['/kpi/default', 'menu'=>'bc4'], ['class' => 'slide-item', 'data-menu' => 'bc4']) ?>
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