<style>
    table {
        border-collapse: collapse;
        width: 100%;
        font-size: 13px;
    }
    th, td {
        border: 1px solid #000;
        padding: 5px;
    }
    th {
        text-align: center;
        font-weight: bold;
    }
</style>

<div style="text-align:center; font-size:18px; font-weight:bold;">
    LỊCH CÔNG TÁC<br>
    <span style="font-size:14px;">(Từ ngày <?= date('d/m/Y') ?>)</span>
</div>

<br>

<table>
    <thead>
        <tr>
            <th>Ngày giờ</th>
            <th>Người thực hiện</th>
            <th>Nội dung công tác</th>
            <th>Địa điểm</th>
            <th>Ghi chú</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $m): ?>
        <tr>
            <td><?= $m->time ?></td>
            <td><?= $m->staff ?></td>
            <td><?= $m->content ?></td>
            <td><?= $m->location ?></td>
            <td><?= $m->note ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
