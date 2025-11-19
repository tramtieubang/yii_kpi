
<?php

use app\modules\employees\models\EmployeesForm;
use kartik\select2\Select2;
use yii\bootstrap5\Modal;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'Đăng ký công việc';
$this->params['breadcrumbs'][] = $this->title;

// Lấy danh sách nhân viên
//$employees = EmployeesForm::find()->orderBy('name')->all();
//$employeeList = ArrayHelper::map($employees, 'id', 'name');

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

                const url = '<?= Url::to(['/work-registered/register/create']) ?>' + '?' + params.toString();

               /*  if (modal) {
                    modal.open({
                        href: url
                    });
                } */
                // Mở AJAX CRUD Modal đúng cách
                // Mở modal và load nội dung
               $.get(url, function(data){
                    if(data.title) {
                        $('#ajaxCrudModal .modal-title').html(data.title);
                    }
                    if(data.content) {
                        $('#ajaxCrudModal .modal-body').html(data.content);
                    }
                    if(data.footer) {
                        $('#ajaxCrudModal .modal-footer').html(data.footer);
                    }
                    $('#ajaxCrudModal').modal('show');
                });


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

    ////////
    document.addEventListener('hidden.bs.modal', function (event) {
        const modals = document.querySelectorAll('.modal.show');
        if (modals.length > 0) {
            document.body.classList.add('modal-open');
        }
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
    'title'  => '',
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
    'title'  => '',
]) ?>
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
