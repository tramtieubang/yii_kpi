<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;

?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* --- Tổng thể --- */
body {
    background-color: #f4f6f8;
    font-family: 'Segoe UI', Roboto, sans-serif;
}

/* --- Dashboard wrapper --- */
.home-dashboard {
    max-width: 1200px;
    margin: 40px auto;
    padding: 10px;
}
.home-dashboard h4 {
    text-align: center;
    margin-bottom: 40px;
    font-size: 22px;
    color: #333;
    font-weight: 600;
}

/* --- Panel nhóm menu --- */
.home-panel {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    padding: 20px 25px;
}
.home-panel h5 {
    font-size: 18px;
    font-weight: 600;
    color: #444;
    margin-bottom: 20px;
    border-left: 5px solid #007bff;
    padding-left: 10px;
}

/* --- Lưới menu --- */
.home-menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);  /* 4 cột mỗi hàng */
    gap: 20px;
    align-items: stretch;
}

/* --- Ô menu --- */
.home-menu-item {
    background: #fff;
    border-radius: 12px;
    text-align: center;
    padding: 25px 10px;
    transition: all 0.3s ease;
    text-decoration: none;
    color: #333;
    border: 2px solid transparent;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}
.home-menu-item i {
    font-size: 34px;
    margin-bottom: 10px;
    transition: all 0.3s ease;
}
.home-menu-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.12);
}

/* --- Hiệu ứng hover riêng cho từng nhóm --- */
.home-menu-admin:hover {
    border-color: #007bff;
    color: #007bff;
}
.home-menu-category:hover {
    border-color: #28a745;
    color: #28a745;
}
.home-menu-product:hover {
    border-color: #ff9800;
    color: #ff9800;
}
.home-menu-invoice:hover {
    border-color: #d10f08;
    color: #d10f08;
}
.home-menu-aluminum:hover {
    border-color: #3A6073;
    color: #3A6073;
}

/* --- Khi hover icon và text đổi cùng màu --- */
.home-menu-item:hover i,
.home-menu-item:hover .home-menu-label {
    color: inherit;
}

/* --- Tên menu --- */
.home-menu-label {
    font-size: 15px;
    font-weight: 600;
    transition: color 0.3s ease;
}

</style>

<div class="home-dashboard">
    <h4>Xin chào, <?= Html::encode(Yii::$app->user->identity->username) ?> 👋</h4>

    <!-- SUPER ADMIN -->
     <?php if (
        PermissionHelper::check('user_management/user/default/index') ||
        PermissionHelper::check('user_management/role/default/index') ||
        PermissionHelper::check('user_management/permission/default/index') ||
        PermissionHelper::check('user_management/permission_group/default/index') ||
        PermissionHelper::check('user_management/session_manager/default/index') 
        ): 
    ?>
        <div class="home-panel">
            <h5><i class="fas fa-crown text-primary"></i> Hệ thống</h5>
            <div class="home-menu-grid">
                <?php if (PermissionHelper::check('/user_management/user/default')): ?>
                    <?= Html::a('<i class="fas fa-users-cog text-primary"></i><div class="home-menu-label">Quản lý Người dùng</div>', ['/user_management/user/default'], ['class' => 'home-menu-item home-menu-admin']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('/user_management/role/default')): ?>
                    <?= Html::a('<i class="fe fe-users text-primary"></i><div class="home-menu-label">Vai trò</div>', ['/user_management/role/default'], ['class' => 'home-menu-item home-menu-admin']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('/user_management/permission/default')): ?>
                    <?= Html::a('<i class="fas fa-user-shield text-primary"></i><div class="home-menu-label">Quyền hạn</div>', ['/user_management/permission/default'], ['class' => 'home-menu-item home-menu-admin']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('/user_management/permission_group/default')): ?>
                    <?= Html::a('<i class="fe fe-layers text-primary"></i><div class="home-menu-label">Nhóm quyền</div>', ['/user_management/permission_group/default'], ['class' => 'home-menu-item home-menu-admin']) ?>
                <?php endif; ?>
                 <?php if (PermissionHelper::check('/user_management/session_manager/default')): ?>
                    <?= Html::a('<i class="fe fe-lock text-primary"></i><div class="home-menu-label">Quản lý phiên đăng nhập </div>', ['/user_management/session_manager/default'], ['class' => 'home-menu-item home-menu-admin']) ?>
                <?php endif; ?>                
            </div>
        </div>
    <?php endif; ?>

    <!-- DANH MUC -->
    <?php if (
        PermissionHelper::check('positions/default/index') ||
        PermissionHelper::check('business-fields/default/index') ||
        PermissionHelper::check('deparments/default/index') ||
	    PermissionHelper::check('employees/default/index') ||
	    PermissionHelper::check('kpi/default/index') 
        ): 
    ?>
        <div class="home-panel">
            <h5><i class="fas fa-chalkboard-teacher text-warning"></i> Danh mục </h5>
            <div class="home-menu-grid">
                <?php if (PermissionHelper::check('positions/default/index')): ?>
                    <?= Html::a('<i class="fe fe-users text-warning"></i><div class="home-menu-label">Chức vụ</div>', ['/positions/default', 'menu' => 'dm1'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'dm1']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('business-fields/default/index')): ?>
                    <?= Html::a('<i class="fe fe-briefcase text-warning"></i><div class="home-menu-label">Lĩnh vực kinh doanh</div>', ['/business-fields/default', 'menu' => 'dm2'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'dm2']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('departments/default/index')): ?>
                    <?= Html::a('<i class="fas fa-building text-warning"></i><div class="home-menu-label">Phòng ban</div>', ['/departments/default', 'menu' => 'dm3'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'dm3']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('employees/default/index')): ?>
                    <?= Html::a('<i class="fe fe-users text-warning"></i><div class="home-menu-label">Nhân viên</div>', ['/employees/default', 'menu' => 'dm4'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'dm4']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-bar-chart-2 text-warning"></i><div class="home-menu-label">Danh mục KPI</div>', ['/kpi/default', 'menu' => 'dm5'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'dm5']) ?>
                <?php endif; ?>
                             
            </div>
        </div>
    <?php endif; ?>

    <!-- QUAN LY CONG VIEC -->
    <?php if (        
        PermissionHelper::check('work-registered/register/index') ||
        PermissionHelper::check('work-assignment/approve/index') ||
	    PermissionHelper::check('staff/default/index') ||
	    PermissionHelper::check('kpi/default/index') 
        ): 
    ?>
        <div class="home-panel">
            <h5><i class="fe fe-briefcase text-info"></i> Quản lý công việc </h5>
            <div class="home-menu-grid">
                <?php if (PermissionHelper::check('work-registered/register/index')): ?>
                    <?= Html::a('<i class="fe fe-edit text-info"></i><div class="home-menu-label">Đăng ký công việc</div>', ['/work-registered/register', 'menu' => 'cv1'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'cv1']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('work-assignment/approve/index')): ?>
                    <?= Html::a('<i class="fe fe-check-square text-info"></i><div class="home-menu-label">Phê duyệt đăng ký</div>', ['/work-assignment/approve', 'menu' => 'cv4'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'cv4']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-share-2 text-info"></i><div class="home-menu-label">Phân công công việc</div>', ['/kpi/default', 'menu' => 'cv5'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'cv5']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-file-text text-info"></i><div class="home-menu-label">Báo cáo công việc</div>', ['/kpi/default', 'menu' => 'cv6'], ['class' => 'home-menu-item home-menu-admin','data-menu' => 'cv6']) ?>
                <?php endif; ?>
                             
            </div>
        </div>
    <?php endif; ?>

    <!-- DANH GIA KPI -->
    <?php if (
        PermissionHelper::check('deparments/default/index') ||
	    PermissionHelper::check('staff/default/index') ||
	    PermissionHelper::check('kpi/default/index') 
        ): 
    ?>
        <div class="home-panel">
            <h5><i class="fe fe-target text-danger"></i> Đánh giá KPI </h5>
            <div class="home-menu-grid">
                <?php if (PermissionHelper::check('deparment/default/index')): ?>
                    <?= Html::a('<i class="fe fe-edit text-danger"></i><div class="home-menu-label">Chấm điểm KPI </div>', ['/deparment/default', 'menu' => 'dg1'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'dg1']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('staff/default/index')): ?>
                    <?= Html::a('<i class="fe fe-user-check text-danger"></i><div class="home-menu-label">Đánh giá hiệu suất cá nhân </div>', ['/staff/default', 'menu' => 'dg2'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'dg2']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-users text-danger"></i><div class="home-menu-label">Đánh giá theo phòng ban </div>', ['/kpi/default', 'menu' => 'dg3'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'dg3']) ?>
                <?php endif; ?>
               
            </div>
        </div>
    <?php endif; ?>                

     <!-- BAO CAO - THONG KE -->
    <?php if (
        PermissionHelper::check('deparments/default/index') ||
	    PermissionHelper::check('employees/default/index') ||
	    PermissionHelper::check('kpi/default/index') 
        ): 
    ?>
        <div class="home-panel">
            <h5><i class="fe fe-pie-chart text-success"></i> Báo cáo - Thống kê </h5>
            <div class="home-menu-grid">
                <?php if (PermissionHelper::check('deparments/default/index')): ?>
                    <?= Html::a('<i class="fe fe-bar-chart-2 text-success"></i><div class="home-menu-label">Báo cáo tổng hợp KPI </div>', ['/deparments/default', 'menu' => 'bc1'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'bc1']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('employees/default/index')): ?>
                    <?= Html::a('<i class="fe fe-activity text-success"></i><div class="home-menu-label">Báo cáo tiến độ công việc </div>', ['/employees/default', 'menu' => 'bc2'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'bc2']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-users text-success"></i><div class="home-menu-label">Báo cáo so sánh nhân viên</div>', ['/kpi/default', 'menu' => 'bc3'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'bc3']) ?>
                <?php endif; ?>
                <?php if (PermissionHelper::check('kpi/default/index')): ?>
                    <?= Html::a('<i class="fe fe-grid text-success"></i><div class="home-menu-label">Báo cáo so sánh phòng ban</div>', ['/kpi/default', 'menu' => 'bc4'], ['class' => 'home-menu-item home-menu-admin', 'data-menu' => 'bc4']) ?>
                <?php endif; ?>
               
            </div>
        </div>
    <?php endif; ?>    
    
</div>
