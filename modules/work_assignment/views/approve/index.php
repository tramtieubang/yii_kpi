<?php

use app\models\KpiWorkRegisteredStatus;
use app\modules\staff\models\StaffForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\grid\GridView;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\work_registered\models\KpiWorkRegisteredSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Quản lý danh sách lịch chưa duyệt';
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
.kpi-btn-primary,
.kpi-btn-reset {
    height: 32px !important;           /* Chiều cao cố định */
    font-size: 13px;                   /* Cỡ chữ */
    display: flex;                     /* Flex căn giữa */
    justify-content: center;           
    align-items: center;               
    gap: 4px;                          
    padding: 0 8px;                    
    border-radius: 4px;                /* Bo góc */
    cursor: pointer;                   
    transition: background-color 0.2s; 
    border: 1px solid #ccc;            /* Viền mặc định */
    background-color: #fff;            /* Nền mặc định */
    color: #333;                        /* Màu chữ mặc định */
}

/* Hiệu ứng hover */
.kpi-btn-primary:hover {
    background-color: #0069d9;
    color: #fff;
    border-color: #0062cc;            /* đổi viền khi hover */
}

.kpi-btn-reset:hover {
    background-color: #f5f5f5;
    color: #333;
    border-color: #999;               /* đổi viền khi hover */
}

/* Toggle filter */
#filterFormBody {
    display:flex;
    flex-wrap:wrap;
    gap:8px;    
    transition: all 0.3s ease;
    overflow:hidden;
    
    /* căn giữa theo chiều ngang */
    justify-content: center;
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
<?php 
    /*  $query = $dataProvider1->query; // gán vào biến $query để IDE hiểu

    // =====================
    // DEBUG SQL
    echo "<pre>";
    echo $query->createCommand()->getRawSql(); // in SQL thực thi
    echo "</pre>"; */

   // echo $sql ?? null;

?>
<!-- ===== Form Filter ===== -->
<div>
    <?= 
        $this->render('//layouts/menus/quanlycongviec/tab_assignment_heading')
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
        'options'=>[
            'class'=>'inline-filter',
            'id'=>'filterForm'
        ],
    ]); ?>   

        <div class="row gx-3 gy-2 justify-content-between" style="padding-top:8px;"><!-- Dòng 1: 4 cột -->
            <div class="col-md-3">
                <label class="form-label mb-1">Ngày giờ bắt đầu từ</label>
                <?= Html::input('datetime-local', 'start_from_date', Yii::$app->request->post('start_from_date'), [
                    'class' => 'form-control form-control-sm',
                ]) ?>
             
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1">Ngày giờ bắt đầu đến</label>
                <?= Html::input('datetime-local', 'start_to_date', Yii::$app->request->post('start_to_date'), [
                    'class' => 'form-control form-control-sm',
                ]) ?>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1">Ngày kết thúc từ</label>
                <?= Html::input('datetime-local', 'end_from_date', Yii::$app->request->post('end_from_date'), [
                    'class' => 'form-control form-control-sm',
                ]) ?>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1">Ngày kết thúc đến</label>
                <?= Html::input('datetime-local', 'end_to_date', Yii::$app->request->post('end_to_date'), [
                    'class' => 'form-control form-control-sm',
                ]) ?>
            </div>
        </div>

        <div class="row gx-3 gy-2 justify-content-between mt-1"><!-- Dòng 2: 4 cột -->
            <div class="col-md-3">
                <label class="form-label mb-1">Nhân viên</label>
                <?php                    
                   $staffs = StaffForm::find()->all();
                    $data = [];
                    foreach ($staffs as $i => $staff) {
                        $stt = $i + 1; // số thứ tự tăng dần
                        $data[$staff->staff_id] = '
                            <div style="display: flex;">
                                <div style="width: 30px;">' . $stt . '</div>
                                <div>' . htmlspecialchars($staff->name) . '</div>
                            </div>
                        ';
                    }

                    echo $form->field($searchModel, 'staff_id')->label(false)->widget(Select2::class, [
                        'options' => ['placeholder' => 'Chọn nhân viên....'],
                        'data' => $data,
                        'pluginOptions' => [
                            'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                            'allowClear' => true,
                            'width' => '100%',
                        ],
                    ]);
                ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($searchModel, 'title', [
                    'labelOptions'=>['class'=>'form-label mb-1'],
                    'template'=>'{label}{input}'
                ])->textInput([
                    'placeholder'=>'Tên công việc',
                    'class'=>'form-control form-control-sm'
                ]) ?>
            </div>

            <div class="col-md-3">
                <?= $form->field($searchModel, 'description', [
                    'labelOptions'=>['class'=>'form-label mb-1'],
                    'template'=>'{label}{input}'
                ])->textInput([
                    'placeholder'=>'Mô tả',
                    'class'=>'form-control form-control-sm'
                ]) ?>
            </div>

            <div class="col-md-3">
                <label class="form-label mb-1">Trạng thái công việc</label>
                <?php 
                   $statuses = KpiWorkRegisteredStatus::find()->all();

                    $stt = 1; // biến số thứ tự

                    $data = ArrayHelper::map($statuses, 'id', function($model) use (&$stt) {
                        $markup = '
                            <div style="display: flex; align-items: center;">
                                <div style="width: 30px; margin-right: 6px;">' . $stt . '</div>
                                <div style="
                                    width: 14px; 
                                    height: 14px; 
                                    background: ' . htmlspecialchars($model->color) . ';
                                    border: 1px solid #ccc; 
                                    border-radius: 3px;
                                    margin-right: 6px;
                                "></div>
                                <div>' . htmlspecialchars($model->name) . '</div>
                            </div>
                        ';
                        $stt++; // tăng số thứ tự
                        return $markup;
                    });

                    echo $form->field($searchModel, 'status_id')->label(false)->widget(Select2::class, [
                        'options' => ['placeholder' => 'Chọn trạng thái...'],
                        'data' => $data,
                        'pluginOptions' => [
                            'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                            'allowClear' => true,
                            'width' => '100%',
                            'templateResult' => new JsExpression('function(item) { return item.text; }'),
                            'templateSelection' => new JsExpression('function(item) { return item.text; }'),
                        ],
                    ]);

                ?>
            </div>
        </div>

        <div class="w-100 d-flex justify-content-center mt-2">
            <?= Html::submitButton('<i class="fe fe-search"></i> Tìm kiếm', ['class'=>'btn kpi-btn-primary btn-sm me-2']) ?>
            <?= Html::resetButton('<i class="fe fe-refresh-cw"></i> Reset', ['class'=>'btn kpi-btn-reset btn-sm','id'=>'btnResetFilter']) ?>
        </div>
        
    </div>
    <?php ActiveForm::end(); ?>
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
                    'heading' => '<span style="color: #f39c12;"><i class="fas fa-clipboard-list"></i> LỊCH CHƯA DUYỆT</span>',
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

jQuery(function ($) {

    $(document).on('ajaxComplete', function (event, xhr) {
        let response;
        try {
            response = JSON.parse(xhr.responseText);
        } catch (e) {
            return; // Không phải JSON
        }

        if (!response.system_id) return;

        const $row = $('tr[data-key="' + response.system_id + '"]');
        if (!$row.length) return;

        // ===== Đây là hàm reload Grid con =====
        const reloadChildGrid = function () {
            const $pjaxChild = $row.next('.kv-expand-detail-row').find('.pjax-jobs-grid');
            if ($pjaxChild.length) {
                $.pjax.reload({
                    container: '#' + $pjaxChild.attr('id'),
                    timeout: 3000,
                    scrollTo: false, // giữ scroll hiện tại
                    replace: false
                });
            }
        };
        // ===== end reloadChildGrid =====

        // Nếu row đang đóng → mở trước rồi reload
        if ($row.hasClass('kv-state-collapsed')) {
            $row.find('.kv-expand-icon').click(); // mở expand
            $(document).one('kvexprow.afterExpand', function () {
                reloadChildGrid();
            });
        } else {
            reloadChildGrid(); // đang mở → reload ngay
        }

    });

});


</script>

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

