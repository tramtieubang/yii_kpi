<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\work_assignment\models\KpiWorkAssignmentForm */
?>
<div class="kpi-work-assignment-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'work_registered_id',
            'staff_id',
            'status_id',
            'kpi_id',
            'start_date',
            'end_date',
            'title',
            'description:ntext',
            'color',
            'assigned_at',
        ],
    ]) ?>

</div>
