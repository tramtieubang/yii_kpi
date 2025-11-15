<?php

use yii\helpers\Html;

?>
<?php if (!Yii::$app->user->isGuest): ?>
    Xin chào, <?= Html::encode(Yii::$app->user->identity->username) ?>!
    <?= Html::a('Đăng xuất', ['/user-management/auth/logout'], ['data-method' => 'post', 'class' => 'btn btn-sm btn-outline-secondary']) ?>
<?php else: ?>
    CHƯA ĐĂNG NHẬP
<?php endif; ?>

main11