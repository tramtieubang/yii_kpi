<?php
use yii\helpers\Html;

$this->title = 'BÁO CÁO  CÔNG VIỆC ĐĂNG KÝ';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .page-title {
        font-size: 16px;
        font-weight:600;
        color: #0d6efd; /* xanh chuẩn Bootstrap */
        display: flex;
        align-items: center;
        padding-top: 15px;
    }
</style>

<div class="work-registered-report-index">

    <div class="page-title mb-2">
        <i class="fas fa-clipboard-list text-primary me-2"></i>
        <span><?= Html::encode($this->title) ?></span>
    </div>

    <?= $this->render('_search', ['searchModel' => $searchModel]) ?>

    <?= $this->render('_grid', ['dataProvider' => $dataProvider]) ?>

</div>
