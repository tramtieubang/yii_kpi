<?php
use yii\bootstrap5\Modal;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = 'Đăng ký công việc';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    /* Pointer khi hover */
    .fc-event,
    .fc-timegrid-slot,
    .fc-daygrid-day {
        cursor: pointer;
    }

    /* Màu event */
    .fc-timegrid-event {
        background-color: #ffc107;
    }

    /* Button refresh */
    .fc-refreshBtn-button {
        color: #fff !important;
        background-color: #5a92a9 !important;
        border-color: #5a92a9 !important;
    }

    .fc-refreshBtn-button.fc-loading-active {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .fc-refreshBtn-button.fc-loading-active::after {
        content: " ⏳";
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { content: " ⏳"; }
        50% { content: " ⌛"; }
        100% { content: " ⏳"; }
    }

    /* ----------------- Customize Header Colors ----------------- */
    /* Month view header */
    .fc-col-header-cell-cushion {
        background-color: #f0ad4e;
        color: #fff;
        font-weight: bold;
    }

    /* Week/Day view column header */
    .fc-col-header-cell {
        background-color: #5bc0de;
        color: #fff;
        font-weight: bold;
    }

    /* Week/Day view time axis */
    .fc-timegrid-axis-cushion {
        background-color: #d9534f;
        color: #fff;
        font-weight: bold;
    }

    #ajaxCrudModal .modal-title,
    #ajaxCrudModal2 .modal-title,
    #ajaxCrudModal3 .modal-title {
        color: #fff; /* chữ trắng */
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
            right: 'dayGridMonth,timeGridWeek,timeGridDay,refreshBtn'
        },

        customButtons: {
            refreshBtn: {
                text: '🔄 Làm mới',
                click: function() {
                    const btn = document.querySelector('.fc-refreshBtn-button');
                    btn.classList.add('fc-loading-active');
                    btn.disabled = true;

                    calendar.refetchEvents();

                    calendar.on('loading', function(isLoading) {
                        if (!isLoading) {
                            btn.classList.remove('fc-loading-active');
                            btn.disabled = false;
                        }
                    });
                }
            }
        },

        initialView: 'timeGridWeek',
        locale: 'vi',
        timeZone: 'local',
        slotMinTime: "06:00:00", // ⬅️ Giờ bắt đầu hiển thị
        slotMaxTime: "24:00:00", //Muốn đặt giờ kết thúc (ví dụ 20:00)?
        //allDaySlot: false, //Nếu muốn ẩn các giờ không dùng?

        events: {
            url: '<?= Url::to(['/work-registered/register/events']) ?>',
            method: 'GET',
            failure: function() {
                alert("Không thể load dữ liệu!");
            }
        },

        navLinks: true,
        businessHours: true,
        editable: false,
        selectable: true,
        dayMaxEvents: true,

        // Click ô trống → create
        select: function(arg) {
            const params = new URLSearchParams({
                start_str: arg.startStr,
                end_str: arg.endStr,
                force_close: true
            });
            const url = '<?= Url::to(['/work-registered/register/create']) ?>' + '?' + params.toString();

            $.get(url, function(data){
                if(data.title) $('#ajaxCrudModal .modal-title').html(data.title);
                if(data.content) $('#ajaxCrudModal .modal-body').html(data.content);
                if(data.footer) $('#ajaxCrudModal .modal-footer').html(data.footer);
                $('#ajaxCrudModal').modal('show');
            });

            calendar.unselect();
        },

        // Click event → update
        eventClick: function(info) {
            const eventId = info.event.id;
            const url = '<?= Url::to(['/work-registered/register/update']) ?>' + '?id=' + eventId;

            $.get(url, function(data){
                if(data.title) $('#ajaxCrudModal .modal-title').html(data.title);
                if(data.content) $('#ajaxCrudModal .modal-body').html(data.content);
                if(data.footer) $('#ajaxCrudModal .modal-footer').html(data.footer);
                $('#ajaxCrudModal').modal('show');
            });
        }

    });

    calendar.render();
});

// Giữ class modal-open nếu còn modal khác
document.addEventListener('hidden.bs.modal', function () {
    const modals = document.querySelectorAll('.modal.show');
    if (modals.length > 0) {
        document.body.classList.add('modal-open');
    }
});
</script>

<!-- // Modal chung cho create/update -->
<?php Modal::begin([
    'options' => [
        'id' => 'ajaxCrudModal',
        'tabindex' => false // important for Select2 to work properly
    ],
    'dialogOptions' => ['class' => 'modal-lg'],
    'closeButton' => ['label' => '<span aria-hidden=\'true\'>×</span>'],
    'id' => 'ajaxCrudModal',
    'footer' => '', // always need it for jquery plugin
    'title' => '',
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
    'title' => '',
]) ?>
<?php Modal::end(); ?>
