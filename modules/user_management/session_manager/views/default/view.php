<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user_management\session_manager\models\UserSessionsForm */
?>
<div class="user-sessions-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'session_id',
            'ip_address',
            'user_agent',
            'device_name',
            'login_time',
            'last_activity',
            'logout_time',
            'revoked_by_admin',
        ],
    ]) ?>

</div>
