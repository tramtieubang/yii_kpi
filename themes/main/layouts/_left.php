<?php

use app\modules\user_management\user\models\User;
use yii\helpers\Html;

?>
<!-- Sidemenu -->
<div class="sticky">
	<div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
	<aside class="app-sidebar ps horizontal-main">
		<div class="app-sidebar__header">
			<a class="main-logo" href="<?= Yii::getAlias('@web/') ?>">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/nguyentrinh-erp-logo.png" class="desktop-logo desktop-logo-dark"
					alt="viboonlogo">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/logo1.png" class="desktop-logo" alt="viboonlogo">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/favicon.png" class="mobile-logo mobile-logo-dark"
					alt="viboonlogo">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/favicon-1.png" class="mobile-logo" alt="viboonlogo">
			</a>
		</div>

		<div class="main-sidemenu">
			<ul class="side-menu">
				<li class="side-item side-item-category" style="background-color:var(--primary-bg-color);padding:15px 20px;color:white;margin-top:-15px"><i class="fa fa-unlock-alt"></i>&nbsp;Xin chào, <?php echo User::getCurrentUser()->username; ?></li>
				<li>
					<a class="side-menu__item" data-bs-toggle="slide" href="<?= Yii::getAlias('@web/') ?>">
						<span class="side-menu__icon"><i class="bi bi-house-door side_menu_img"></i></span>
						<span class="side-menu__label">Trang chủ</span>
					</a>
				</li>
				
				<!-- <li class="side-item side-item-category">CHỨC NĂNG</li> -->
				<?= $this->render('menus/system') ?>
				
			</ul>
			<div class="slide-right" id="slide-right">
				<svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191" width="24" height="24"
					viewBox="0 0 24 24">
					<path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z">
					</path>
				</svg>
			</div>
		</div>
	</aside>
</div>
<!-- End Sidemenu -->