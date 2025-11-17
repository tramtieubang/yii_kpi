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
             [
                'label' => 'Tên đăng nhập',
                'value' => function($model) {
                    return $model->user ? $model->user->username : '-';
                }
            ],
            [
                'label' => 'Phòng ban',
                'value' => function($model) {
                    return $model->department ? $model->department->name : '-';
                }
            ],
            [
                'label' => 'Chức vụ',
                'value' => function($model) {
                    return $model->position ? $model->position->name : '-';
                }
            ],
            [
                'label' => 'Lĩnh vực kinh doanh',
                'value' => function($model) {
                    return $model->businessField ? $model->businessField->name : '-';
                }
            ],
            'name',
            'email:email',
            'phone',
            'hire_date:date',
            'created_at:datetime',
            'updated_at:datetime',
        ],
    ]) ?>

</div>
