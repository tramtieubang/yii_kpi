<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\role\models\RoleForm */
?>
<div class="role-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            'created_at',
            'updated_at',
            'group_code',
        ],
    ]) ?>

</div>
