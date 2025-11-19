<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use app\modules\employees\models\EmployeesForm;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\work_registered\models\KpiWorkRegisteredSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh sách';
$this->params['breadcrumbs'][] = $this->title;

// Lấy danh sách nhân viên
$employees = EmployeesForm::find()->orderBy('name')->all();
$employeeList = ArrayHelper::map($employees, 'id', 'name');
?>

<style>
/* ===== Card chung ===== */
.kpi-card {
    border:1px solid #e3e6ea;
    border-radius:8px;
    overflow:hidden;
    margin-bottom:10px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
}

/* Header gọn */
.kpi-card-header {
    background:#f4f6f8;
    border-bottom:1px solid #e1e4e8;
    padding:4px 16px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.kpi-card-header h6 {
    font-size:14px;
    font-weight:600;
    margin:0;
    display:flex;
    align-items:center;
    color:#333;
}
.kpi-card-header i {
    font-size:16px;
    margin-right:6px;
}

/* Input & Select */
.kpi-input .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
    padding-left: 6px;
}
.kpi-input .select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 28px !important;
    top: 2px;
}
.kpi-input{
    padding:0px 5px !important; 
    margin:0px 2px !important; 
}
.kpi-input .form-label {
    font-size:12px;
    font-weight:500;
    margin-bottom:4px !important;
    padding:5px 4px !important; 
}

/* Button */
.kpi-btn-primary, .kpi-btn-reset {
    height:32px !important;
    font-size:13px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:4px;
    padding:0 8px;
}

/* Toggle filter */
#filterFormBody {
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    padding:8px 0;
    transition: all 0.3s ease;
    overflow:hidden;
}
#filterFormBody.collapsed {
    height:0;
    padding:0;
    margin:0;
    opacity:0;
}

/* Nút ẩn/hiện */
.btn-scroll {
    border:none;
    background:none;
    font-size:16px;
    cursor:pointer;
}

/* GridView */
.kv-panel-heading, .kv-panel-footer {
    display:flex !important;
    justify-content:space-between;
    align-items:center;
    padding:4px 16px !important;
}
.kv-panel .kv-summary {
    margin-right:auto;
}
.kv-panel .kv-toolbar {
    margin-left:auto;
}
.kv-panel .table-responsive {
    margin:0 !important;
    padding:0 !important;
}
</style>

<!-- ===== Form Filter ===== -->
<div>
    <?= 
        $this->render('//layouts/menus/quanlycongviec/gridview_heading')
    ?>
</div>
<div class="card kpi-card shadow-sm mb-3">
    <div class="card-header kpi-card-header">
        <h6><i class="fe fe-filter"></i> Lọc dữ liệu</h6>
        <button class="btn-scroll" type="button" id="btnScrollFilter">
            <i class="fe fe-chevron-up"></i>
        </button>
    </div>

    <div class="card-body p-2" id="filterFormBody">
        <?php $form = ActiveForm::begin([
            'method'=>'post',
            'action'=>['filter'],
            'options'=>['class'=>'inline-filter d-flex flex-wrap align-items-end gap-2','id'=>'filterForm'],
        ]); ?>

        <!-- Ngày bắt đầu -->
        <div class="kpi-input" style="width:180px;">
            <?= $form->field($searchModel, 'date_start', [
                'labelOptions'=>['class'=>'form-label mb-1'],
            ])->widget(DatePicker::class, [
                'options'=>['placeholder'=>'Ngày bắt đầu','class'=>'form-control form-control-sm kv-date'],
                'pluginOptions'=>['autoclose'=>true,'format'=>'dd/mm/yyyy','todayHighlight'=>true],
            ]) ?>
        </div>

        <!-- Ngày kết thúc -->
        <div class="kpi-input" style="width:180px;">
            <?= $form->field($searchModel, 'date_end', [
                'labelOptions'=>['class'=>'form-label mb-1'],
            ])->widget(DatePicker::class, [
                'options'=>['placeholder'=>'Ngày kết thúc','class'=>'form-control form-control-sm kv-date'],
                'pluginOptions'=>['autoclose'=>true,'format'=>'dd/mm/yyyy','todayHighlight'=>true],
            ]) ?>
        </div>

        <!-- Nhân viên -->
        <div class="kpi-input" style="width:180px;">
            <?= $form->field($searchModel, 'employee_id', [
                'labelOptions'=>['class'=>'form-label mb-1'],
                'template'=>'{label}{input}',
            ])->widget(Select2::class, [
                'data'=>$employeeList,
                'options'=>['placeholder'=>'Chọn nhân viên ...','class'=>'form-control form-control-sm'],
                'pluginOptions'=>['allowClear'=>true,'width'=>'100%'],
            ]) ?>
        </div>

        <!-- Tên công việc -->
        <div class="kpi-input" style="width:185px;">
            <?= $form->field($searchModel, 'title', ['labelOptions'=>['class'=>'form-label mb-1']])
                     ->textInput(['placeholder'=>'Tên công việc','class'=>'form-control form-control-sm']) ?>
        </div>

        <!-- Mô tả -->
        <div class="kpi-input" style="width:185px;">
            <?= $form->field($searchModel, 'description', ['labelOptions'=>['class'=>'form-label mb-1']])
                     ->textInput(['placeholder'=>'Mô tả','class'=>'form-control form-control-sm']) ?>
        </div>

        <!-- Nút tìm kiếm / reset -->
        <div class="w-100 d-flex justify-content-center mt-2">
            <?= Html::submitButton('<i class="fe fe-search"></i> Tìm kiếm', ['class'=>'btn kpi-btn-primary btn-sm me-2']) ?>
            <?= Html::resetButton('<i class="fe fe-refresh-cw"></i> Reset', ['class'=>'btn kpi-btn-reset btn-sm','id'=>'btnResetFilter']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>

<!-- ===== PJAX Grid ===== -->
<?php Pjax::begin(['id'=>'myGrid','timeout'=>10000,'formSelector'=>'#filterForm']); ?>
<div class="kpi-work-registered-form-index">
    <div id="ajaxCrudDatatable">
        <?= GridView::widget([
            'id'=>'crud-datatable',
            'dataProvider'=>$dataProvider,
            'pjax'=>true,
            'columns'=>require(__DIR__.'/_columns.php'),
            'striped'=>false,
            'condensed'=>true,
            'responsive'=>false,
            'toolbar'=>[
                ['content'=>
                    '<div class="dropdown">
                        <button class="btn dropdown-toggle" data-bs-toggle="dropdown" type="button"><i class="fa fa-navicon"></i></button>
                        <div class="dropdown-menu tx-13">
                            <h6 class="dropdown-header tx-uppercase tx-11 tx-bold bg-info tx-spacing-1">Chọn chức năng</h6>'
                    . Html::a('<i class="fas fa fa-plus"></i> Thêm mới', ['create'], ['role'=>'modal-remote','title'=>'Thêm mới','class'=>'dropdown-item'])
                    . Html::a('<i class="fas fa fa-sync"></i> Tải lại', [''], ['data-pjax'=>1,'class'=>'dropdown-item'])
                    . Html::a('<i class="fas fa fa-trash"></i> Xóa danh sách', ['bulkdelete'], ['role'=>'modal-remote-bulk','class'=>'dropdown-item text-secondary'])
                    . '</div></div>' . '{export}'
                ],
            ],
            'panel'=>[
                'heading'=>'<i class="typcn typcn-folder-open"></i> XEM DANH SÁCH',
                'headingOptions'=>['class'=>'card-header rounded-bottom-0 card-header text-dark'],
                'before'=>false,
            ],
            'panelHeadingTemplate' => '<div style="width:100%; display:flex; justify-content:space-between; align-items:center;">
                <div class="kv-title">{title}</div>
                <div class="kv-toolbar">{toolbar}</div>
            </div>',
            'panelFooterTemplate' => '<div style="width:100%; display:flex; justify-content:space-between; align-items:center;">
                <div class="kv-summary">{summary}</div>
                <div class="kv-pager">{pager}</div>
            </div>',
            'summary'=>'Tổng: {totalCount} dòng dữ liệu',
            'export'=>['options'=>['class'=>'btn']]
        ]) ?>
    </div>
</div>
<?php Pjax::end(); ?>

<!-- ===== JS ===== -->
<script>
    // Toggle filter
    const btnScrollFilter = document.getElementById('btnScrollFilter');
    const filterBody = document.getElementById('filterFormBody');
    btnScrollFilter.addEventListener('click', () => {
        const icon = btnScrollFilter.querySelector('i');
        filterBody.classList.toggle('collapsed');
        icon.classList.toggle('fe-chevron-up');
        icon.classList.toggle('fe-chevron-down');
    });

    // Reset filter
    document.getElementById('btnResetFilter').addEventListener('click', function(e){
        e.preventDefault();
        const form = this.closest('form');
        form.reset();
        $(form).find('select').val(null).trigger('change');
        $(form).find('input.kv-date').each(function(){ $(this).datepicker('update',''); });
    });
</script>

<?php 
// ===== Modal =====
Modal::begin(['options'=>['id'=>'ajaxCrudModal','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'']); Modal::end();
Modal::begin(['options'=>['id'=>'ajaxCrudModal2','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'']); Modal::end();
Modal::begin(['options'=>['id'=>'ajaxCrudModal3','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'']); Modal::end();
?>
