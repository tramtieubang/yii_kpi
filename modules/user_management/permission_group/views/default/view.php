<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\permission_group\models\PermissionGroupForm */
?>
<div class="permission-group-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'code',
            'name',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
