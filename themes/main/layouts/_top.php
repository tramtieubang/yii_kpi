<?php

namespace app\modules\user\controllers;

use yii\widgets\ActiveForm;
use app\modules\user\models\User;
use Yii;
use yii\helpers\Html;

?>
<!--Main Header -->
<div class="main-header side-header sticky">
	<div class="container-fluid main-container">
		<div class="main-header-left sidemenu" style="padding-right: 10px;">
			<a class="main-header-menu-icon1" href="javascript:void(0);" data-bs-toggle="sidebar"
				id="mainSidebarToggle"><span class="fa fa-list-ul"></span></a>
		</div>
		<div class="main-header-left horizontal">
			<a class="main-logo" href="<?= Yii::getAlias('@web/') ?>">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/nguyentrinh-erp-logo.png" class="desktop-logo desktop-logo-dark" alt="viboonlogo">
				<img src="<?= Yii::getAlias('@web') ?>/assets/images/brand/logo1.png" class="desktop-logo theme-logo" alt="viboonlogo">
			</a>
		</div>
		<?php if(Yii::$app->params['showTopSearch'] != false):?>
		<div class="">
			<form class="myFilterForm input-icon" method="post">
				<div class="input-icon-addon">
					<span class="header-serach-btn">
						<i class="fe fe-search"></i>
					</span>
				</div>
				<input name="search" type="search" class="form-control header-search" placeholder="Tìm kiếm&hellip;"
					tabindex="1">
			</form>
		</div>
		<?php endif;  ?>
		<div class="main-header-right ms-auto">
			<button class="navbar-toggler navresponsive-toggler d-lg-none ms-auto collapsed" type="button"
				data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent-4"
				aria-controls="navbarSupportedContent-4" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon fe fe-more-vertical"></span>
			</button>
			<div class="navbar navbar-expand-lg navbar-collapse responsive-navbar p-0">
				<div class="collapse navbar-collapse" id="navbarSupportedContent-4">
					<ul class="nav nav-item header-icons navbar-nav-right ms-auto">
						<!-- Country-selector-->
						<!-- <li class="dropdown header-language-selection">
							<a href="javascript:void(0);" class="nav-link icon" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
								<i class="bi bi-translate"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
								<ul class="header-dropdown-list">
									<li>
										<div class="dropdown-item d-flex align-items-center position-relative">
											<a href="#;" class="stretched-link"></a>
											<span>French</span>
										</div>
									</li>
									<li>
										<div class="dropdown-item d-flex align-items-center position-relative">
											<a href="#;" class="stretched-link"></a>
											<span>Germany</span>
										</div>
									</li>
									<li>
										<div class="dropdown-item d-flex align-items-center position-relative">
											<a href="#;" class="stretched-link"></a>
											<span>Italy</span>
										</div>
									</li>
									<li>
										<div class="dropdown-item d-flex align-items-center position-relative">
											<a href="#;" class="stretched-link"></a>
											<span>Russia</span>
										</div>
									</li>
									<li>
										<div class="dropdown-item d-flex align-items-center position-relative">
											<a href="#;" class="stretched-link"></a>
											<span>Spain</span>
										</div>
									</li>
								</ul>
							</div>
						</li>-->
						<!-- Country-selector-->
						<!-- Theme-Layout -->
						<!-- <li class="dropdown main-header-notification d-md-flex">
							<a class="nav-link icon theme-layout nav-link-bg layout-setting"
								href="javascript:void(0);">
								<span class="dark-layout"><i class="bi bi-cloud-moon"></i></span>
								<span class="light-layout"><i class="bi bi-cloud-sun"></i></span>
							</a>
						</li> -->
						<!-- Theme-Layout -->
						<li class="dropdown header-search">
							<a class="nav-link icon header-search" data-bs-toggle="dropdown" href="#">
								<i class="fe fe-search"></i>
							</a>
							<div class="dropdown-menu">
								<div class="main-form-search p-2">
									<input class="form-control" placeholder="Search" type="search">
									<button class="btn"><i class="fe fe-search mt-2 "></i></button>
								</div>
							</div>
						</li>


						<!-- FULL SCREEN -->
						<li class="dropdown d-none d-md-flex mt-1">
							<a class="nav-link icon full-screen-link" title="Chế độ toàn màn hình">
								<i class="fe fe-maximize fullscreen-button floating" id="fullscreen-button"></i>
							</a>
						</li>

						<!-- SETTINGS -->
						<!-- <li class="dropdown d-none d-md-flex mt-1">
							<a class="nav-link icon" title="Cấu hình tài khoản">
								<i class="fe fe-settings floating"></i>
							</a>
						</li> -->

						<li class="dropdown d-none d-md-flex mt-1">
							<?= Html::a('<i class="fe fe-user floating"></i>', ['/staff/default/edit-profile', 'menu'=>'ns3'], [
								'style' => 'margin-right: 10px;',
								'title' => 'Cập nhật thông tin cá nhân',
							]); ?>
						</li>

						<!-- LOGOUT -->
						<li class="dropdown d-none d-md-flex mt-1">
							<?= Html::a('<i class="fe fe-power floating"></i>', ['/user_management/user/auth/logout'], ['title' => 'Đăng xuất']); ?>
						</li>

						<!-- SHORTCUTS -->
						<!-- 
						<li class="dropdown main-header-notification shortcuts d-flex">
							<a class="nav-link icon" href="#" data-bs-toggle="dropdown" data-bs-auto-close="outside">
								<i class="bi bi-card-heading"></i>
							</a>
							<div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
								<div class="header-navheading d-flex border-bottom mb-0 align-items-center">
									<h5 class="fw-semibold mb-0">Shortcuts</h5>
								</div>
								<ul class="drop-icon-wrap">
									<li>
										<a href="mail-inbox.html" class="drop-icon-item">
											<i class="fe fe-mail text-dark"></i>
											<span class="d-block">E-mail</span>
										</a>
									</li>
									<li>
										<a href="full-calendar.html" class="drop-icon-item">
											<i class="fe fe-calendar text-dark"></i>
											<span class="d-block">calendar</span>
										</a>
									</li>
									<li>
										<a href="map-leaflet.html" class="drop-icon-item">
											<i class="fe fe-map-pin text-dark"></i>
											<span class="d-block">map</span>
										</a>
									</li>
									<li>
										<a href="product-cart.html" class="drop-icon-item">
											<i class="fe fe-shopping-cart text-dark"></i>
											<span class="d-block">Cart</span>
										</a>
									</li>
									<li>
										<a href="chat.html" class="drop-icon-item">
											<i class="fe fe-message-square text-dark"></i>
											<span class="d-block">chat</span>
										</a>
									</li>
									<li>
										<a href="contacts.html" class="drop-icon-item">
											<i class="fe fe-phone-outgoing text-dark"></i>
											<span class="d-block">contact</span>
										</a>
									</li>
								</ul>
							</div>
						</li>-->
						<!-- notification -->
						<!-- 
						<li class="dropdown header-settings">
							<a href="javascript:void(0);" class="nav-link icon" data-bs-toggle="sidebar-right"
								data-bs-target=".sidebar-right">
								<i class="bi bi-text-indent-right"></i>
							</a>
						</li>
						 -->
						<?php /* ?>
						<li class="dropdown d-flex main-profile-menu">

							<a class="main-img-user d-flex" href="javascript:void(0);"
								data-bs-toggle="dropdown">
								<img alt="avatar" src="<?= Yii::getAlias('@web') ?>/uploads/icons/user.png">
							</a>

							<div class="dropdown-menu dropdown-menu-arrow">
								<div class="header-navheading">
									<h6 class="main-notification-title mb-1"><?= User::getCurrentUser()->username ?></h6>
									<span class="tx-13 text-muted"><?= User::getCurrentUser()->tenNhanVien ?></span>
									<!-- <br/><span class="tx-13 text-muted"><?= User::getCurrentUser()->chucVu ?></span>-->
								</div>
								<!-- <a class="dropdown-item border-top text-wrap" href="<?= Yii::getAlias('@web') ?>/user/info">
									<i class="fe fe-user"></i> <span class="lh-1">Thông tin</span>
								</a> -->
								<!-- <a class="dropdown-item text-wrap" href="<?= Yii::getAlias('@web') ?>/user/info-edit">
									<i class="fe fe-edit"></i> <span class="lh-1">Edit Profile</span>
								</a> -->
								<a class="dropdown-item text-wrap" href="<?= Yii::getAlias('@web') ?>/user/auth/change-own-password">
									<i class="fe fe-lock "></i> <span class="lh-1">Thay đổi mật khẩu</span>
								</a>
								<!--<a class="dropdown-item text-wrap" href="<?= Yii::getAlias('@web') ?>/user/user/activity">
									<i class="fe fe-activity"></i> <span class="lh-1">Hoạt động gần đây</span>
								</a>  -->
								<a class="dropdown-item text-wrap" href="<?= Yii::getAlias('@web') ?>/user/auth/logout">
									<i class="fe fe-power"></i> <span class="lh-1">Đăng xuất</span>
								</a>
							</div>

						</li>
						<?php /*/ ?>
					</ul>
				</div>
			</div>

			<!-- <div class="switcher-icon nav-link icon sidebar-right1  fe-spin">
				<i class="bi bi-gear  floating"></i>
			</div> -->

		</div>
	</div>
</div>
<!--Main Header -->