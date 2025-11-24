<?php

use app\modules\staff\models\StaffForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\work_registered\models\KpiWorkRegisteredSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh sách';
$this->params['breadcrumbs'][] = $this->title;

// Lấy danh sách nhân viên
$staff = StaffForm::find()->orderBy('name')->all();
$staffList = ArrayHelper::map($staff, 'id', 'name');
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

.table-group-row {
    background: #f0f3f7;
    font-weight: bold;
    font-size: 16px;
}

.job-row {
    background: #fff;
}

.hidden-job {
    display: none !important;
}

.toggle-jobs {
    font-size: 18px;
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
            <?= $form->field($searchModel, 'staff_id', [
                'labelOptions'=>['class'=>'form-label mb-1'],
                'template'=>'{label}{input}',
            ])->widget(Select2::class, [
                'data'=>$staffList,
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

 <?php Pjax::begin([
        'id' => 'crud-datatable-pjax',
        'timeout' => 10000,
        'formSelector' => '.myFilterForm'
    ]); ?>
    

    <div class="card-white">
       
        <!-- GridView -->
        <div id="ajaxCrudDatatable">
            <?= GridView::widget([
                'id' => 'work-registered-grid',
                'dataProvider' => $dataProvider,
                'pjax' => false,
                'panel' => false,
                'summary' => false,
                'responsive' => true,
                'striped' => false,
                'condensed' => true,
                'hover' => true,
                'columns' => require(__DIR__.'/_columns.php'),
                'toolbar'=> [
                    ['content'=>
                        Html::a('<i class="fas fa fa-plus" aria-hidden="true"></i> Thêm công việc mới', ['create'],
                        ['role'=>'modal-remote','title'=> 'Thêm mới Users','class'=>'btn btn-outline-primary']).
                        Html::a('<i class="fas fa fa-sync" aria-hidden="true"></i> Tải lại', [''],
                        ['data-pjax'=>1, 'class'=>'btn btn-outline-primary', 'title'=>'Tải lại']).
                      
                        //'{toggleData}'.
                        '{export}'
                    ],
                ],    
                'striped' => false,
                'condensed' => true,
                'responsive' => true,   
                'panelHeadingTemplate'=>'{title}',
                'panelFooterTemplate'=>'{summary}',
                'summary'=>'Hiển thị dữ liệu {count}/{totalCount}, Trang {page}/{pageCount}',
                'panel' => [
                    //'type' => 'primary', 
                    //'heading' => '<i class="fas fa fa-list" aria-hidden="true"></i> Danh sách',
                    //'heading' => $this->render('//layouts\menus/gridview_heading'),
                    'heading' => 'NHẬT KÝ CÔNG VIỆC ĐĂNG KÝ',
                    'headingOptions' => ['class'=>'card-header'],
                    'before'=>'<em>* '.Html::encode($this->title).'</em>',
                    '<div class="clearfix"></div>',
                ],  

            ]); ?>
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

    document.addEventListener('hidden.bs.modal', function (event) {
        const modals = document.querySelectorAll('.modal.show');
        if (modals.length > 0) {
            document.body.classList.add('modal-open');
        }
    });    


// Khi modal đóng
/* $(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal.show').length > 0) {
        $('body').addClass('modal-open'); // giữ scroll
    }
}); */


 // ✅ Lắng nghe mọi AJAX phản hồi (khi thêm/sửa/xóa)
$(document).on('ajaxComplete', function (event, xhr) {
    let response;
    try {
        response = JSON.parse(xhr.responseText);
    } catch (e) {
        return; // Không phải JSON, bỏ qua
    }

    // ✅ Nếu có systemId → chỉ reload ExpandRow tương ứng
    if (response.system_id) {
        const $row = $('tr[data-key="' + response.system_id + '"]');

        // Nếu hàng đang expand thì reload nội dung con
        if ($row.hasClass('kv-state-expanded')) {
            const $expandCell = $row.next('.kv-expand-detail-row');
            const $expandContainer = $expandCell.find('.kv-detail-content');

            if ($expandContainer.length) {
                $.pjax.reload({
                    container: $expandContainer.find('.pjax-jobs-grid').attr('id'),
                    async: false
                });
            } else {
                // Nếu không có PJAX con, fallback: đóng mở lại
                const expandBtn = $row.find('.kv-expand-icon');
                expandBtn.click();
                setTimeout(() => expandBtn.click(), 600);
            }
        }
    }

});



</script>

<?php 
// ===== Modal =====
/* Modal::begin(['options'=>['id'=>'ajaxCrudModal','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'', 'title'=>'']); Modal::end();
Modal::begin(['options'=>['id'=>'ajaxCrudModal2','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'', 'title'=>'']); Modal::end();
Modal::begin(['options'=>['id'=>'ajaxCrudModal3','tabindex'=>false],'dialogOptions'=>['class'=>'modal-lg'],'closeButton'=>['label'=>'×'],'footer'=>'', 'title'=>'']); Modal::end(); */
?>

<?php Modal::begin([
   'options' => [
        'id'=>'ajaxCrudModal',
        'tabindex' => false // important for Select2 to work properly
   ],
   'dialogOptions'=>['class'=>'modal-lg'],
   'closeButton'=>['label'=>'<span aria-hidden=\'true\'>×</span>'],
   'id'=>'ajaxCrudModal',
    'footer'=>'',// always need it for jquery plugin
])?>

<?php Modal::end(); ?>

<?php Modal::begin([
   'options' => [
        'id'=>'ajaxCrudModal2',
        'tabindex' => false // important for Select2 to work properly
   ],
   'dialogOptions'=>['class'=>'modal-xl'],
   'closeButton'=>['label'=>'<span aria-hidden=\'true\'>×</span>'],
   'id'=>'ajaxCrudModal2',
    'footer'=>'',// always need it for jquery plugin
])?>

<?php Modal::end(); ?>

<?php Modal::begin([
   'options' => [
        'id'=>'ajaxCrudModal3',
        'tabindex' => false // important for Select2 to work properly
   ],
   'dialogOptions'=>['class'=>'modal-lg'],
   'closeButton'=>['label'=>'<span aria-hidden=\'true\'>×</span>'],
   'id'=>'ajaxCrudModal3',
    'footer'=>'',// always need it for jquery plugin
])?>

<?php Modal::end(); ?>

