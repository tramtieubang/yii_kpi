<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\work_registered\models\KpiWorkRegisteredForm */
?>
<div class="kpi-work-registered-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'employee_id',
            'kpi_id',
            'title',
            'description:ntext',
            'status',
            'date_start',
            'date_end',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
