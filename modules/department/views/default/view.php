<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\departments\models\DepartmentsForm */
?>
<div class="departments-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'code',
            'name',
            'description:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
