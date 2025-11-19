<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;

$currentModule = Yii::$app->controller->module->id;
$currentController = Yii::$app->controller->id;
$currentAction = Yii::$app->controller->action->id ?? 'index';

// Mảng menu với data-menu riêng biệt
$menuItems = [
    [
        'label' => 'Đăng ký công việc',
        'icon' => 'fe fe-clipboard',
        'url' => ['/work-registered/register', 'menu'=>'cv1'],
        'permission' => '/work-registered/register',
        'controller' => 'register',
        'actions' => ['index','view','update'],
        'dataMenu' => 'cv-register'
    ],
    [
        'label' => 'Lịch làm việc',
        'icon' => 'fa-solid fa-calendar-days',
        'url' => ['/work-registered/calendar', 'menu'=>'cv1'],
        'permission' => '/work-registered/calendar',
        'controller' => 'calendar',
        'actions' => ['index'],
        'dataMenu' => 'cv-calendar'
    ],
    [
        'label' => 'Nhật ký công việc',
        'icon' => 'fa-solid fa-history',
        'url' => ['/work-registered/default', 'menu'=>'cv1'],
        'permission' => '/work-registered/default',
        'controller' => 'default',
        'actions' => ['index'],
        'dataMenu' => 'cv-default'
    ],
];
?>

<style>
.gridheading-menu {
  display: flex;
  align-items: center;
  gap: 22px;
  background: #fff;
  padding: 6px 20px;
  font-family: 'Segoe UI', sans-serif;
  font-size: 13px;
  line-height: 1.2;
}

.gridheading-menu a {
  text-decoration: none;
  color: #555; /* màu mặc định */
  font-weight: 500;
  position: relative;
  padding-bottom: 4px;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: all 0.2s ease-in-out;
}

.gridheading-menu a i {
  font-size: 12px;
}

.gridheading-menu a:hover {
  color: #007bff;
}

.gridheading-menu a.active {
  color: #007bff;
  border-bottom: 2px solid #007bff;
}

.gridheading-menu a.active::after {
  content: '';
  position: absolute;
  bottom: -9px;
  left: 50%;
  transform: translateX(-50%) rotate(45deg);
  width: 6px;
  height: 6px;
  background: #007bff;
}
</style>

<div class="gridheading-menu">
<?php foreach ($menuItems as $item): ?>
    <?php if (PermissionHelper::check($item['permission'])): ?>
        <?php 
            // Chỉ active nếu module + controller + action khớp
            $active = ($currentModule == 'work-registered' 
                       && $currentController == $item['controller'] 
                       && in_array($currentAction, $item['actions'] ?? ['index']))
                      ? ' active' 
                      : '';
        ?>
        <?= Html::a(
            "<i class=\"{$item['icon']}\"></i> {$item['label']}",
            $item['url'],
            [
                'class' => 'slide-item' . $active, 
                'data-menu' => $item['dataMenu']
            ]
        ); ?>
    <?php endif; ?>
<?php endforeach; ?>
</div>
