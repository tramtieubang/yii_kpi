<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
?>

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
                'columns' => require(__DIR__.'/_columns.php'),
                'toolbar'=> [
                    ['content'=>
                        Html::button('<i class="fas fa-file-pdf"></i> Xuất PDF', [
                            'class' => 'btn btn-outline-danger btn-md',
                            'id' => 'btn-export-pdf',
                            'title' => 'Xuất PDF',
                            'data-pjax' => 0,
                        ]).
                       
                        Html::button('<i class="fas fa-file-word"></i> Xuất Word', [
                                'class' => 'btn btn-outline-primary btn-md',
                                'id' => 'btn-export-word',
                                'title' => 'Xuất Word',
                                'data-pjax' => 0,       // KHÔNG dùng PJAX
                            ]
                        ).

                        Html::button('<i class="fas fa-file-excel"></i> Xuất Excel', [
                                'class' => 'btn btn-outline-success btn-md',
                                'id' => 'btn-export-excel',
                                'title' => 'Xuất Excel',
                                'data-pjax' => 0,       // KHÔNG dùng PJAX
                            ]
                        ).

                        Html::a('<i class="fas fa fa-sync" aria-hidden="true"></i> Tải lại', [''],
                        ['data-pjax'=>1, 'class'=>'btn btn-outline-primary', 'title'=>'Tải lại'])
                      
                        //'{toggleData}'.
                        //'{export}'
                    ],
                ],    

                //'panel' => false,
                //'summary' => false,
                'responsive' => true,
                'striped' => false,
                'condensed' => true,
                'hover' => true,
                'panelHeadingTemplate'=>'{title}',
                //'panelFooterTemplate'=>'{summary}',
                //'summary'=>'Hiển thị dữ liệu {count}/{totalCount}, Trang {page}/{pageCount}',
                'panelFooterTemplate'=>'<div style="width:100%;"><div class="float-start">{summary}</div><div class="float-end">{pager}</div></div>',
                'summary'=>'Tổng: {totalCount} dòng dữ liệu',
                // 🔹 Vị trí hiển thị nút trang
                'layout' => "{items}\n{pager}",
                'panel' => [
                    //'type' => 'primary', 
                    //'heading' => '<i class="fas fa fa-list" aria-hidden="true"></i> Danh sách',
                    'headingOptions' => [
                        'class' => 'gv-heading-mini'
                    ],
                    'before' => '<div style="
                                    color:#007bff;
                                    border:0px solid black;
                                    height:35px;
                                    display:flex;
                                    align-items:center;      /* canh giữa theo chiều cao */
                                    /*justify-content:center;  /* canh giữa ngang */
                                    font-weight:600;
                                ">
                                    <i class="fas fa-clipboard-list" style="margin-right:6px;"></i>
                                    DANH SÁCH CÔNG VIỆC ĐĂNG KÝ
                                </div>
                                ',
                    '<div class="clearfix"></div>',
                ],
            ]); ?>
        </div>       
       
    </div>

<?php Pjax::end(); ?>

<script>
    // Toggle filter
    const btnScrollFilter = document.getElementById('btnScrollFilter');
    const filterBody = document.getElementById('filterFormBody');

    let isCollapsed = false;

    btnScrollFilter.addEventListener('click', () => {
        const icon = btnScrollFilter.querySelector('i');

        if (!isCollapsed) {
            // Đóng
            filterBody.style.height = filterBody.scrollHeight + 'px';
            requestAnimationFrame(() => {
                filterBody.style.height = '0px';
                filterBody.classList.add('is-collapsed');
            });
        } else {
            // Mở
            filterBody.classList.remove('is-collapsed');
            filterBody.style.height = filterBody.scrollHeight + 'px';

            filterBody.addEventListener('transitionend', function handler() {
                filterBody.style.height = 'auto';
                filterBody.removeEventListener('transitionend', handler);
            });
        }

        btnScrollFilter.classList.toggle('is-collapsed');
        icon.classList.toggle('fe-chevron-up');
        icon.classList.toggle('fe-chevron-down');

        isCollapsed = !isCollapsed;
    });
  
    
</script>

<?php
$this->registerJs(<<<JS

    $('#btn-export-pdf').on('click', function (e) {
        e.preventDefault();

        let form = $('#filterForm');   // form chứa dữ liệu filter

        $.ajax({
            url: '/work-registered/report-export/pdf-tuan',
            type: 'POST',
            data: form.serialize(),
            xhrFields: {
                responseType: 'blob'      // quan trọng
            },
            success: function (data, status, xhr) {

                let blob = new Blob([data], { type: 'application/pdf' });

                // Lấy filename từ header (nếu có)
                let filename = "bao_cao_lich_cong_tac.pdf";
                let disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    let match = disposition.match(/filename="?([^"]+)"?/);
                    if (match.length > 1) filename = match[1];
                }

                // Tạo link tải
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
            },
            error: function (xhr) {
                alert("Xuất PDF thất bại!");
            }
        });

    });

    /* Word */
    $('#btn-export-word').on('click', function (e) {
        e.preventDefault();
        let form = $('#filterForm');
        $.ajax({
            url: '/work-registered/report-export/word-tuan',
            type: 'POST',
            data: form.serialize(),
            xhrFields: { responseType: 'blob' },
            success: function (data, status, xhr) {
                let blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
                let filename = "bao_cao_lich_cong_tac.docx";
                let disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    let match = disposition.match(/filename="?([^"]+)"?/);
                    if (match.length > 1) filename = match[1];
                }
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
            },
            error: function () {
                alert("Xuất Word thất bại!");
            }
        });
    });

    /* Excel */
    $('#btn-export-excel').on('click', function (e) {
        e.preventDefault();
        let form = $('#filterForm');
        $.ajax({
            url: '/work-registered/report-export/excel-tuan',
            type: 'POST',
            data: form.serialize(),
            xhrFields: { responseType: 'blob' },
            success: function (data, status, xhr) {
                let blob = new Blob([data], { type: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' });
                let filename = "bao_cao_lich_cong_tac.docx";
                let disposition = xhr.getResponseHeader('Content-Disposition');
                if (disposition && disposition.indexOf('filename=') !== -1) {
                    let match = disposition.match(/filename="?([^"]+)"?/);
                    if (match.length > 1) filename = match[1];
                }
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;
                document.body.appendChild(link);
                link.click();
                link.remove();
            },
            error: function () {
                alert("Xuất Word thất bại!");
            }
        });
    });

JS);
?>