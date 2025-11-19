
<?php

use app\modules\employees\models\EmployeesForm;
use kartik\select2\Select2;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Lịch trình kinh doanh';
$this->params['breadcrumbs'][] = ['label' => 'Nhật ký liên hệ', 'url' => ['/contact', 'menu' => 'kh2']];
$this->params['breadcrumbs'][] = $this->title;
Yii::$app->params['showSearch'] = false;
Yii::$app->params['showView'] = true;

// Lấy danh sách nhân viên
$employees = EmployeesForm::find()->orderBy('name')->all();
$employeeList = ArrayHelper::map($employees, 'id', 'name');

?>
<style>
    .fc-timegrid-event {
        background-color: #ffc107;
    }

    .fc-refreshBtn-button {
        color: #fff !important;
        background-color: #5a92a9 !important;
        border-color: #5a92a9 !important;
    }
</style>

<div>
    <?= 
        $this->render('//layouts/menus/quanlycongviec/gridview_heading')
    ?>
</div>

<div class="card border-default p-4">
    <div class="row mb-3">
        <?php
            /* Lấy danh sách nhân viên */
            $items = EmployeesForm::find()->all();
            $data = [];
            foreach ($items as $index => $emp) {
                $data[$emp->id] = '<div style="display: flex; align-items: center;">
                                        <div style="width:24px; text-align:left; margin-right:5px;">'.($index + 1).'</div> 
                                        <div>'.$emp->name.'</div>
                                    </div>';
            }
        ?>
        <div class="col-md-3">
            <label class="fw-bold">Chọn nhân viên:</label>
            <?= Select2::widget([
                'name' => 'employee_id', // name input
                'data' => $data,
                'options' => [
                    'placeholder' => '-- Chọn nhân viên --',
                ],
                'pluginOptions' => [
                    'escapeMarkup' => new JsExpression('function(markup) { return markup; }'), // cho phép render HTML
                    'allowClear' => true,
                    'width' => '100%', // thêm dòng này
                    // Nếu Select2 nằm trong modal, bật dòng này:
                    // 'dropdownParent' => new JsExpression("$('#ajaxCrudModal .modal-body')"),
                ],
            ]); ?>
        </div>

    </div>

    <div id="calendar2" class="calendar"></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar2');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay, refreshBtn'
            },
            customButtons: {
                refreshBtn: {
                    text: 'Làm mới',
                    click: function() {
                        window.location.reload();
                    }
                }
            },
            navLinks: true, // can click day/week names to navigate views
            businessHours: true, // display business hours
            editable: true,
            selectable: true,
            selectMirror: true,
            initialView: 'timeGridWeek',
            locale: 'vi',
            timeZone: 'local',
            slotLabelFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            },
            droppable: false,
            editable: false,
            dayMaxEvents: true, // allow "more" link when too many events
            select: function(arg) {
                const params = new URLSearchParams({
                    start_str: arg.startStr,
                    end_str: arg.endStr,
                    force_close: true
                });

                const url = '<?= Url::to(['/contact/default/create']) ?>' + '?' + params.toString();

                if (modal) {
                    modal.open({
                        href: url
                    });
                }

                calendar.unselect()
            },
            eventDidMount: function(info) {
                if (info.event.extendedProps.role) {
                    info.el.setAttribute('role', info.event.extendedProps.role);
                }
            },
           
        });

        calendar.render();
    });
</script>

<?php Modal::begin([
    'options' => [
        'id' => 'ajaxCrudModal',
        'tabindex' => false // important for Select2 to work properly
    ],
    'dialogOptions' => ['class' => 'modal-lg'],
    'closeButton' => ['label' => '<span aria-hidden=\'true\'>×</span>'],
    'id' => 'ajaxCrudModal',
    'footer' => '', // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>

<?php Modal::begin([
    'options' => [
        'id' => 'ajaxCrudModal2',
        'tabindex' => false // important for Select2 to work properly
    ],
    'dialogOptions' => ['class' => 'modal-lg'],
    'closeButton' => ['label' => '<span aria-hidden=\'true\'>×</span>'],
    'id' => 'ajaxCrudModal2',
    'footer' => '', // always need it for jquery plugin
]) ?>
<?php Modal::end(); ?>