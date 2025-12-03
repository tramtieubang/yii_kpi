<?php
use yii\bootstrap5\Modal;
use yii\helpers\Url;

$this->title = 'Lịch trình kinh doanh';
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

    .fc-tooltip {
        box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        white-space: nowrap;
        pointer-events: none;
    }

</style>

<div>
    <?= $this->render('//layouts/menus/quanlycongviec/tab_registered_heading') ?>
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
                text: 'Làm mới',
                click: function() { window.location.reload(); }
            }
        },
        locale: 'vi',
        timeZone: 'local',
        initialView: 'timeGridWeek',
        editable: false,
        selectable: true,
        selectMirror: true,
        dayMaxEvents: true,
        navLinks: true,
        businessHours: true,
        slotLabelFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
        eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },

        // Load dữ liệu từ server
        events: {
            url: '<?= Url::to(['/work-registered/calendar/events']) ?>',
            method: 'GET',
            failure: function() {
                alert("Không thể load dữ liệu!");
            }
        },

        // Click vào event -> mở modal xem chi tiết
        eventClick: function(info) {
            const eventId = info.event.id;
            const url = '<?= Url::to(['/work-registered/calendar/view']) ?>' + '?id=' + eventId;

            $.get(url, function(data){
                if(data.title) $('#ajaxCrudModal .modal-title').html(data.title);
                if(data.content) $('#ajaxCrudModal .modal-body').html(data.content);
                if(data.footer) $('#ajaxCrudModal .modal-footer').html(data.footer);
                $('#ajaxCrudModal').modal('show');
            });
        },

        // Không mở modal khi select
        select: function(arg) {
            calendar.unselect();
        },

        eventDidMount: function(info) {
            if(info.event.extendedProps.color){
                info.el.style.backgroundColor = info.event.extendedProps.color;
            }
            info.el.style.cursor = 'pointer';
        },

        // ================================
        //  POPUP HIỆN TRẠNG THÁI KHI RÊ CHUỘT
        // ================================
        eventMouseEnter: function(info) {

            let tooltip = document.createElement('div');
            tooltip.className = 'fc-tooltip';
            tooltip.style.position = 'absolute';
            tooltip.style.zIndex = '9999';
            tooltip.style.padding = '6px 10px';
            tooltip.style.background = 'rgba(0,0,0,0.75)';
            tooltip.style.color = '#fff';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '13px';
            tooltip.style.pointerEvents = 'none';

           /*  const status = info.event.extendedProps.status ?? 'Không có';
            const start = info.event.start.toLocaleString('vi-VN');
            const end = info.event.end ? info.event.end.toLocaleString('vi-VN') : '';

            tooltip.innerHTML = `
                <b>${info.event.title}</b><br>
                Trạng thái: <span style="color:#ffd700">${status}</span><br>
                Thời gian: ${start} - ${end}
            `; */

            let staff = info.event.extendedProps.staff ?? 'Không có';
            let status = info.event.extendedProps.status ?? 'Không có';
            let start = info.event.start.toLocaleString('vi-VN');
            let end = info.event.end ? info.event.end.toLocaleString('vi-VN') : '';

            tooltip.innerHTML = `
                <b>${staff}</b><br>
                <b>${info.event.title}</b><br>
                Trạng thái: <span style="color:#ffd700">${status}</span><br>
                Thời gian: ${start}${end ? ' - ' + end : ''}
            `;

            document.body.appendChild(tooltip);

            // Di chuyển theo chuột
            info.el.addEventListener('mousemove', function(e) {
                tooltip.style.top = (e.pageY + 10) + 'px';
                tooltip.style.left = (e.pageX + 10) + 'px';
            });

            // Rời chuột thì xóa
            info.el.addEventListener('mouseleave', function() {
                tooltip.remove();
            });

            info.el._tooltip = tooltip;
        },

        eventMouseLeave: function(info) {
            if (info.el._tooltip) info.el._tooltip.remove();
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