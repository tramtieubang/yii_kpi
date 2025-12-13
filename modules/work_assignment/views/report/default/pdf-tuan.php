<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<style>
    body {
        font-family: 'Times New Roman', Times, serif;
        font-size: 13px;
        color: #333;
    }

    .header {
        width: 100%;
        display: flex;
        justify-content: flex-end; /* Logo bên phải */
        align-items: center;
        margin-bottom: 10px;
    }

    .header-right img {
        width: 80px;
        height: auto;
    }

    .title {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
        margin-top: 30px;
        margin-bottom: 5px;
    }

    .sub-title {
        text-align: center;
        font-size: 13px;
        margin-bottom: 15px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
    }

    th, td {
        border: 1px solid #999;
        padding: 6px;
    }

    th {
        background-color: #f2f2f2;
        text-align: center;
    }

    td {
        vertical-align: top;
    }

    .footer {
        width: 100%;
        margin-top: 20px;
        font-size: 13px;
    }

    .footer table {
        width: 100%;
        border: none;
    }

    .footer td {
        text-align: center;
        border: none;
        vertical-align: top;
    }

    /* Chữ ký đậm, gạch dài và căn giữa */
    .footer .signature {
        font-weight: bold;
        /*letter-spacing: 3px;*/
        display: block;
        margin-top: 20px;
    }

    .footer .signature-line {
        padding-top: 150px;
    }
</style>
</head>
<body>

<div class="header">
    <div class="header-right">
         <img src="/images/bgnt.png" alt="Logo">
    </div>
</div>

<div class="title">Báo cáo công việc tuần</div>
<div class="sub-title">
    (Từ ngày ${tu_ngay} – đến ngày ${den_ngay})
</div>

<table>
    <thead>
        <tr>
            <th style="width:40px;">STT</th>
            <th>Ngày</th>
            <th>Công việc</th>
            <th>Người thực hiện</th>
            <th>Kết quả</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
         ${data}
    </tbody>
</table>

<div class="footer">
    <table>
        <tr>
            <td class="signature">Người lập báo cáo</td>
            <td class="signature">Thủ trưởng đơn vị</td>
        </tr>
        <tr>
            <td class="signature-line">_________________________</td>
            <td class="signature-line">_________________________</td>
        </tr>
    </table>
</div>

</body>
</html>
