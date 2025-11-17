<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\employees\models\EmployeesForm */
?>
<div class="employees-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'user_id',
            'department_id',
            'name',
            'email:email',
            'phone',
            'position',
            'hire_date',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
