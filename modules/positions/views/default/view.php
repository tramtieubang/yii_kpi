<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\positions\models\PositionsForm */
?>
<div class="positions-form-view">
 
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'description:ntext',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
