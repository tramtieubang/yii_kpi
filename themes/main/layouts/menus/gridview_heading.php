<?php

use app\custom\PermissionHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$currentModule = Yii::$app->controller->module->id;

?>

<style>
.gridheading-menu {
  display: flex;
  align-items: center;
  gap: 22px;
  background: #fff;
  padding: 6px 20px;
/*   border-bottom: 1px solid #ddd; */
  font-family: 'Segoe UI', sans-serif;
  font-size: 13px;
  line-height: 1.2;
}

.gridheading-menu a {
  text-decoration: none;
  color: #555;
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
    <?php if (PermissionHelper::check('/alsystems/default')): ?>
        <?= Html::a(
            '<i class="fas fa-bullseye"></i> Hệ thống nhôm',
            ['/alsystems/default'],
            ['class' => 'home-menu-item' . ($currentModule == 'alsystems' ? ' active' : '')]
        ) ?>
    <?php endif; ?>

    <?php if (PermissionHelper::check('/alaluminummaterials/default')): ?>
        <?= Html::a(
            '<i class="fa-solid fa-cube"></i> Vật liệu nhôm đầu vào',
            ['/alaluminummaterials/default'],
            ['class' => 'home-menu-item' . ($currentModule == 'alaluminummaterials' ? ' active' : '')]
        ) ?>
    <?php endif; ?>

    <?= Html::a(
        '<i class="fas fa-file-invoice-dollar"></i> Báo giá',
        ['#'],
        ['class' => 'home-menu-item' . ($currentModule == 'quotation' ? ' active' : '')]
    ) ?>

    <?= Html::a(
        '<i class="fas fa-shopping-cart"></i> Đơn hàng',
        ['#'],
        ['class' => 'home-menu-item' . ($currentModule == 'order' ? ' active' : '')]
    ) ?>

    <?= Html::a(
        '<i class="fas fa-receipt"></i> Hóa đơn',
        ['#'],
        ['class' => 'home-menu-item' . ($currentModule == 'invoice' ? ' active' : '')]
    ) ?>

    <?= Html::a(
        '<i class="fas fa-chart-pie"></i> Báo cáo',
        ['#'],
        ['class' => 'home-menu-item' . ($currentModule == 'report' ? ' active' : '')]
    ) ?>

    <?= Html::a(
        '<i class="fas fa-ellipsis-h"></i> Khác',
        ['#'],
        ['class' => 'home-menu-item' . ($currentModule == 'other' ? ' active' : '')]
    ) ?>
</div>
