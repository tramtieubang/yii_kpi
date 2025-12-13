<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 13px;
        margin: 0;
        padding: 0;
    }

    .title-section {
        text-align: center;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .title-main {
        font-size: 20px;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .title-sub {
        font-size: 13px;
        margin-top: 3px;
        color: #444;
        font-style: italic;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th {
        background: #f2f2f2;
        padding: 6px;
        font-weight: bold;
        border: 1px solid #000;
        text-align: center;
    }

    td {
        border: 1px solid #000;
        padding: 6px;
        vertical-align: top;
    }

    /* -------------------------
       GỘP DÒNG CÙNG NGÀY
       ------------------------- */

    /* Dòng trùng ngày: bỏ border-top */
    tr.no-border td {
        border-top: none !important;
    }

    /* Giữ border trái/phải/bottom để không bị lệch */
    tr.no-border td:first-child {
        border-left: 1px solid #000 !important;
    }

    tr.no-border td:last-child {
        border-right: 1px solid #000 !important;
    }

    .footer-sign {
        margin-top: 40px;
        width: 100%;
        text-align: center;
    }

    .footer-col {
        width: 33%;
        float: left;
        font-size: 13px;
    }

    .note {
        font-size: 11px;
        color: #666;
        font-style: italic;
        margin-top: 5px;
    }
</style>

<div class="title-section">
    <div class="title-main">LỊCH LÀM VIỆC</div>
    <div class="title-sub">(Từ ngày ${tu_ngay} – đến ngày ${den_ngay})</div>
</div>

<table>
    <thead>
        <tr>
            <th style="width:18%;">Ngày</th>
            <th style="width:42%;">Nội dung công việc</th>
            <th style="width:25%;">Người thực hiện</th>
            <th style="width:15%;">Ghi chú</th>
        </tr>
    </thead>
    <tbody>
        ${data}
    </tbody>
</table>

<div class="footer-sign">
    <div class="footer-col">
        <strong>Người lập</strong><br>
        <span class="note">(Ký và ghi rõ họ tên)</span>
    </div>

    <div class="footer-col">
        <strong>Trưởng bộ phận</strong><br>
        <span class="note">(Ký phê duyệt)</span>
    </div>

    <div class="footer-col">
        <strong>Lãnh đạo đơn vị</strong><br>
        <span class="note">(Ký – ghi rõ họ tên)</span>
    </div>
</div>

<div style="clear: both;"></div>
