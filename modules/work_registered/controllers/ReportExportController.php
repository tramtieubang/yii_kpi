<?php
namespace app\modules\work_registered\controllers;

use app\custom\PermissionHelper;
use app\modules\work_assignment\models\KpiWorkAssignmentForm;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use app\modules\work_registered\models\KpiWorkRegisteredSearch;
use Yii;
use yii\web\Controller;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;
use PhpOffice\PhpWord\TemplateProcessor;
use yii\web\Response;

class ReportExportController extends Controller
{
    // ----------------------- PDF -----------------------    
    // Khong gom dong
    public function actionPdfTuan1()
    {
        $searchModel = new KpiWorkRegisteredSearch();

        // Lấy dữ liệu POST hoặc query
        $postData = Yii::$app->request->post();
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['start_date' => SORT_ASC]
        ];
        $dataProvider->pagination = false;

        $dates = array_column($dataProvider->getModels(), 'start_date');
        $tu_ngay = $dates ? Yii::$app->formatter->asDate(min($dates), 'php:d/m/Y') : '';
        $den_ngay = $dates ? Yii::$app->formatter->asDate(max($dates), 'php:d/m/Y') : '';


        // Gom dữ liệu theo ngày
        $rowsByDate = [];
        $dates = [];

         $stt = 1;
        $data = '';
        foreach ($dataProvider->getModels() as $row) {
            $data .= "
            <tr>
                <td style='text-align:center'>{$stt}</td>
                <td style='text-align:center'>".Yii::$app->formatter->asDate($row->start_date)."</td>
                <td>{$row->description}</td>
                <td>{$row->staff->name}</td>
                <td>{$row->status->name}</td>
                <td></td>
            </tr>";
            $stt++;
        }

        // Render template
        $html = file_get_contents(Yii::getAlias('@app/modules/work_registered/views/report/default/pdf-tuan.php'));
        $html = strtr($html, [
            '${data}' => $data,
            '${tu_ngay}' => $tu_ngay,
            '${den_ngay}' => $den_ngay,
        ]);

        // Tạo PDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'DejaVu Sans',
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('bao_cao_lich_cong_tac.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // Co gom dong
    public function actionPdfTuan()
    {
        $searchModel = new KpiWorkRegisteredSearch();

        // Lấy dữ liệu POST hoặc query
        $postData = Yii::$app->request->post();
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['start_date' => SORT_ASC]
        ];
        $dataProvider->pagination = false;

        $models = $dataProvider->getModels();
        $rowsByDate = [];
        $dates = [];

        // Gom dữ liệu theo ngày
        foreach ($models as $row) {
            if (!$row) continue;

            $dateKey = $row->start_date ? Yii::$app->formatter->asDate($row->start_date, 'php:Y-m-d') : '';
            if ($row->start_date) $dates[] = $row->start_date;
            $rowsByDate[$dateKey][] = $row;
        }

        // Xuất HTML bảng với rowspan cho STT + Ngày
        $data = '';
        $stt = 1;
        foreach ($rowsByDate as $dateKey => $items) {
            $displayDate = $dateKey ? Yii::$app->formatter->asDate($dateKey, 'php:d/m/Y') : '';
            $rowspan = count($items);
            $first = true;

            foreach ($items as $detail) {
                $data .= "<tr>";

                // Cột STT + Ngày dùng rowspan
                if ($first) {
                    $data .= "<td rowspan='{$rowspan}' style='text-align:center; font-weight:bold;'>{$stt}</td>";
                    $data .= "<td rowspan='{$rowspan}' style='text-align:center; font-weight:bold;'>{$displayDate}</td>";
                    $first = false;
                    $stt++;
                }

                // Các cột khác
                $data .= "
                    <td>{$detail->description}</td>
                    <td>{$detail->staff->name}</td>
                    <td>{$detail->status->name}</td>
                    <td></td>
                ";

                $data .= "</tr>";
            }
        }

        // Tính từ ngày - đến ngày
        if (!empty($dates)) {
            sort($dates);
            $tu_ngay  = Yii::$app->formatter->asDate($dates[0], 'php:d/m/Y');
            $den_ngay = Yii::$app->formatter->asDate(end($dates), 'php:d/m/Y');
        } else {
            $tu_ngay = '';
            $den_ngay = '';
        }

        // Render template
        $html = file_get_contents(Yii::getAlias('@app/modules/work_registered/views/report/default/pdf-tuan.php'));
        $html = strtr($html, [
            '${data}' => $data,
            '${tu_ngay}' => $tu_ngay,
            '${den_ngay}' => $den_ngay,
        ]);

        // Tạo PDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'DejaVu Sans',
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('bao_cao_lich_cong_tac.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    public function actionPdf()
    {
        $searchModel = new KpiWorkRegisteredSearch();

        // Lấy dữ liệu POST hoặc query
        $postData = Yii::$app->request->post();
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['start_date' => SORT_ASC]
        ];
        $dataProvider->pagination = false;

        // Gom dữ liệu theo ngày
        $rowsByDate = [];
        $dates = [];

        foreach ($dataProvider->getModels() as $detail) {
            if (!$detail) continue;

            if ($detail->start_date) {
                $dateKey = Yii::$app->formatter->asDate($detail->start_date, 'php:Y-m-d');
                $dates[] = $detail->start_date;
            } else {
                $dateKey = '';
            }

            $rowsByDate[$dateKey][] = $detail;
        }

        // Xuất HTML bảng với ROWSPAN
        $data = '';

        foreach ($rowsByDate as $dateKey => $items) {

            $displayDate = $dateKey
                ? Yii::$app->formatter->asDate($dateKey, 'php:d/m/Y')
                : '';

            $rowspan = count($items);
            $first = true;

            foreach ($items as $detail) {
                $data .= "<tr>";

                // Cột Ngày – Giờ (dùng rowspan)
                if ($first) {
                    $data .= "<td rowspan='{$rowspan}' style='text-align:center; font-weight:bold;'>{$displayDate}</td>";
                    $first = false;
                }

                // Các cột khác
                $data .= "
                    <td>{$detail->description}</td>
                    <td>{$detail->staff->name}</td>
                    <td></td>
                ";

                $data .= "</tr>";
            }
        }

        // Tính từ ngày - đến ngày
        if (!empty($dates)) {
            sort($dates);
            $tu_ngay  = Yii::$app->formatter->asDate($dates[0], 'php:d/m/Y');
            $den_ngay = Yii::$app->formatter->asDate(end($dates), 'php:d/m/Y');
        } else {
            $tu_ngay = '';
            $den_ngay = '';
        }

        // Render template
        $html = file_get_contents(Yii::getAlias('@app/modules/work_registered/views/report/default/pdf.php'));
        $html = strtr($html, [
            '${data}' => $data,
            '${tu_ngay}' => $tu_ngay,
            '${den_ngay}' => $den_ngay,
        ]);

        // Tạo PDF
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 10,
            'margin_right' => 5,
            'margin_top' => 10,
            'margin_bottom' => 10,
            'default_font' => 'DejaVu Sans',
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output('bao_cao_lich_cong_tac.pdf', \Mpdf\Output\Destination::DOWNLOAD);
    }

    // ----------------------- WORD -----------------------

    public function actionWordTuan()
    {
        Yii::$app->response->format = Response::FORMAT_RAW;
        $this->layout = false;

        // 1️⃣ Lấy dữ liệu
        $searchModel = new KpiWorkRegisteredSearch();
        $postData = Yii::$app->request->post();
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);
        $dataProvider->sort = [
            'defaultOrder' => ['start_date' => SORT_ASC]
        ];

        $dataProvider->pagination = false;
        $models = $dataProvider->getModels();

        // 2️⃣ Load template
        $templatePath = Yii::getAlias('@app/modules/work_registered/views/report/default/template-word.docx');
        if (!file_exists($templatePath)) {
            throw new \yii\web\NotFoundHttpException("Template Word không tồn tại: {$templatePath}");
        }
        $templateProcessor = new TemplateProcessor($templatePath);

        // 3️⃣ Thời gian báo cáo
        $dates = array_filter(array_map(fn($d) => $d->start_date, $models));
        $templateProcessor->setValue('tu_ngay', $dates ? Yii::$app->formatter->asDate(min($dates), 'php:d/m/Y') : '');
        $templateProcessor->setValue('den_ngay', $dates ? Yii::$app->formatter->asDate(max($dates), 'php:d/m/Y') : '');

        // 4️⃣ Clone row bảng
        $templateProcessor->cloneRow('stt', count($models));

        foreach ($models as $i => $detail) {
            $index = $i + 1;
            $templateProcessor->setValue("stt#{$index}", $index);
            $templateProcessor->setValue("date#{$index}", $detail->start_date ? Yii::$app->formatter->asDate($detail->start_date,'php:d/m/Y') : '');
            $templateProcessor->setValue("description#{$index}", $detail->description);
            $templateProcessor->setValue("staff#{$index}", $detail->staff->name);
            $templateProcessor->setValue("status#{$index}", $detail->status->name);
            $templateProcessor->setValue("note#{$index}", $detail->note ?? '');
        }

        // 5️⃣ Header & Footer (logo + ngày)
        $templateProcessor->setImageValue('logo', [
            'path' => Yii::getAlias('@app/web/images/logo.png'),
            'width' => 80,
            'height' => 80,
            'ratio' => true,
        ]);
        $templateProcessor->setValue('ngay_in', date('d/m/Y'));

        // 6️⃣ Xuất file Word
        $fileName = 'bao_cao_tuan.docx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $templateProcessor->saveAs('php://output');
        exit;
    }

    public function actionWord()
    {
        $models = KpiWorkRegisteredSearch::find()->all();

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        // Tiêu đề
        $section->addText("LỊCH CÔNG TÁC", ['bold' => true, 'size' => 16], ['align' => 'center']);

        // Bảng
        $table = $section->addTable([
            'borderSize' => 6,
            'borderColor' => '000000',
        ]);

        // Header
        $table->addRow();
        $table->addCell()->addText("Ngày giờ");
        $table->addCell()->addText("Người thực hiện");
        $table->addCell()->addText("Nội dung công tác");
        $table->addCell()->addText("Địa điểm");
        $table->addCell()->addText("Ghi chú");

        // Data
        foreach ($models as $m) {
            $table->addRow();
            $table->addCell()->addText($m->time);
            $table->addCell()->addText($m->staff);
            $table->addCell()->addText($m->content);
            $table->addCell()->addText($m->location);
            $table->addCell()->addText($m->note);
        }

        $filename = "lich_cong_tac.docx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("php://output");
        exit;
    }

    // ----------------------- EXCEL -----------------------

    public function actionExcelTuan()
    {
        $templateFile = Yii::getAlias('@app/modules/work_registered/views/report/default/template-excel.xlsx');
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($templateFile);
        $sheet = $spreadsheet->getActiveSheet();

        // 1️⃣ Ghi dữ liệu đơn (single cell)
        //$sheet->setCellValue('B2', 'BÁO CÁO CÔNG VIỆC TUẦN');
        //$sheet->setCellValue('A4', date('d/m/Y'));

        // 2️⃣ Lấy dữ liệu
        Yii::$app->response->format = Response::FORMAT_RAW;

        $searchModel = new KpiWorkRegisteredSearch();
        $postData = Yii::$app->request->post();
        $dataProvider = $searchModel->search($postData ?: Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['start_date' => SORT_ASC]];
        $dataProvider->pagination = false;
        $models = $dataProvider->getModels();

        // Thời gian báo cáo
        $dates = array_filter(array_map(fn($d) => $d->start_date, $models));
        $TuNgayDenNgay = "Từ ngày " . Yii::$app->formatter->asDate(min($dates), 'php:d/m/Y') . 
                        ' đến ngày ' . Yii::$app->formatter->asDate(max($dates), 'php:d/m/Y');
        $sheet->setCellValue('A4', $TuNgayDenNgay);

        /* ----------------------------------------------------
        *                 ĐỔ DỮ LIỆU CÁCH 2 (MERGE THEO NGÀY)
        * ---------------------------------------------------- */
        $row = 7;
        $stt = 1;

        // Nhóm dữ liệu theo ngày
        $grouped = [];
        foreach ($models as $m) {
            $grouped[$m->start_date][] = $m;
        }

        foreach ($grouped as $date => $items) {

            $startRow = $row;
            $endRow = $row + count($items) - 1;

            // Merge cột A (STT) và cột B (Ngày)
            $sheet->mergeCells("A{$startRow}:A{$endRow}");
            $sheet->mergeCells("B{$startRow}:B{$endRow}");

            // Ghi STT + Ngày
            $sheet->setCellValue("A{$startRow}", $stt++);
            $sheet->setCellValue("B{$startRow}", Yii::$app->formatter->asDate($date));

            // Duyệt từng công việc trong 1 ngày
            foreach ($items as $item) {

                $sheet->setCellValue("C{$row}", $item->description);
                $sheet->setCellValue("D{$row}", $item->staff->name);
                $sheet->setCellValue("E{$row}", $item->status->name);
                $sheet->setCellValue("F{$row}", $item->note ?? '');

                // Set chiều cao dòng cho đẹp
                $sheet->getRowDimension($row)->setRowHeight(22);

                $row++;
            }
        }

        /* ----------------------------------------------------
        *                3️⃣ TỰ ĐỘNG KẺ Ô + CANH GIỮA
        * ---------------------------------------------------- */

        // Vùng cần kẻ bảng (từ header row → row cuối)
        $borderRange = "A7:F" . ($row - 1);

        // Kẻ border
        $styleBorder = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000']
                ]
            ]
        ];
        $sheet->getStyle($borderRange)->applyFromArray($styleBorder);

        // Căn giữa chiều dọc cho toàn vùng
        $sheet->getStyle($borderRange)
            ->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

        // Căn giữa STT + Ngày theo chiều ngang luôn
        $sheet->getStyle("A7:A" . ($row - 1))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle("B7:B" . ($row - 1))
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


        /* -----------------------------------------
        * 5️⃣ THÊM FOOTER "Người lập báo cáo – Thủ trưởng đơn vị"
        * ----------------------------------------- */
        $footerRow1 = $row + 1;
        $footerRow2 = $row + 8;

        // Dòng 1: tiêu đề
        $sheet->setCellValue("B{$footerRow1}", "Người lập báo cáo");
        $sheet->setCellValue("E{$footerRow1}", "Thủ trưởng đơn vị");

        // In đậm + căn giữa
        $sheet->getStyle("B{$footerRow1}:E{$footerRow1}")
            ->getFont()->setBold(true);

        // Căn giữa
        $sheet->getStyle("B{$footerRow1}:E{$footerRow1}")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Dòng 2: gạch chân
        $sheet->setCellValue("B{$footerRow2}", "_________________________");
        $sheet->setCellValue("E{$footerRow2}", "_________________________");

        $sheet->getStyle("B{$footerRow2}:E{$footerRow2}")
            ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            

        /* ----------------------------------------------------
        *                 4️⃣ XUẤT FILE
        * ---------------------------------------------------- */

        $filename = "BaoCao_" . date('Ymd_His') . ".xlsx";
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;
        Yii::$app->response->headers->add('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        Yii::$app->response->headers->add('Content-Disposition', "attachment; filename=\"$filename\"");
        Yii::$app->response->headers->add('Cache-Control', 'max-age=0');

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return $content;
    }

    public function actionExcel()
    {
        $models = KpiWorkRegisteredSearch::find()->all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Header
        $sheet->setCellValue('A1', 'Ngày giờ');
        $sheet->setCellValue('B1', 'Người thực hiện');
        $sheet->setCellValue('C1', 'Nội dung công tác');
        $sheet->setCellValue('D1', 'Địa điểm');
        $sheet->setCellValue('E1', 'Ghi chú');

        // Data
        $row = 2;
        foreach ($models as $m) {
            $sheet->setCellValue("A{$row}", $m->time);
            $sheet->setCellValue("B{$row}", $m->staff);
            $sheet->setCellValue("C{$row}", $m->content);
            $sheet->setCellValue("D{$row}", $m->location);
            $sheet->setCellValue("E{$row}", $m->note);
            $row++;
        }

        // Xuất file
        $filename = "lich_cong_tac.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    // Register
    public function actionPrintRegister($start = null, $end = null)
    {
        // Chuẩn hóa ngày
        if ($start) $start = date('Y-m-d 00:00:00', strtotime($start));
        if ($end)   $end   = date('Y-m-d 23:59:59', strtotime($end));

        $query = KpiWorkRegisteredForm::find()
            ->andFilterWhere(['>=', 'start_date', $start])
            ->andFilterWhere(['<=', 'start_date', $end]);

        // ----- Kiểm tra quyền duyệt -----
        $canApprove = PermissionHelper::check('work-assignment/approve/index');

        if (!Yii::$app->user->isSuperadmin && !$canApprove) {
            $query->andWhere(['staff_id' => Yii::$app->user->id]);
        }

        // --- ActiveDataProvider ---
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['start_date' => SORT_ASC],
            ],
        ]);

        $models = $dataProvider->getModels();

        // -------- Gom nhóm theo ngày --------
        $rowsByDate = [];
        $dates = [];

        foreach ($models as $row) {
            if (!$row) continue;

            $dateKey = Yii::$app->formatter->asDate($row->start_date, 'php:Y-m-d');

            $rowsByDate[$dateKey][] = $row;
            $dates[] = $row->start_date;
        }

        // -------- Tạo HTML bảng --------
        $data = '';
        $stt = 1;

        foreach ($rowsByDate as $dateKey => $items) {
            $displayDate = Yii::$app->formatter->asDate($dateKey, 'php:d/m/Y');
            $rowspan = count($items);
            $first = true;

            foreach ($items as $detail) {
                $data .= "<tr>";

                if ($first) {
                    $data .= "<td rowspan='{$rowspan}'>{$stt}</td>";
                    $data .= "<td rowspan='{$rowspan}'>{$displayDate}</td>";
                    $first = false;
                    $stt++;
                }

                $data .= "
                    <td>{$detail->description}</td>
                    <td>{$detail->staff->name}</td>
                    <td>{$detail->status->name}</td>
                    <td></td>
                ";

                $data .= "</tr>";
            }
        }

        // Tính từ ngày - đến ngày
        if (!empty($dates)) {
            sort($dates);
            $tu_ngay  = Yii::$app->formatter->asDate($dates[0], 'php:d/m/Y');
            $den_ngay = Yii::$app->formatter->asDate(end($dates), 'php:d/m/Y');
        } else {
            $tu_ngay = $den_ngay = '';
        }

        // Render template HTML
        $html = file_get_contents(Yii::getAlias('@app/modules/work_registered/views/report/register/pdf-tuan.php'));
        $html = strtr($html, [
            '${data}' => $data,
            '${tu_ngay}' => $tu_ngay,
            '${den_ngay}' => $den_ngay,
        ]);

        // ----- Tạo PDF -----
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'DejaVu Sans',
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output("bao_cao_lich_dang_ky.pdf", "I"); // Mở tab PDF
    }

    // Calendar
    public function actionPrintCalendar($start = null, $end = null)
    {
        // Chuẩn hóa ngày
        if ($start) $start = date('Y-m-d 00:00:00', strtotime($start));
        if ($end)   $end   = date('Y-m-d 23:59:59', strtotime($end));

        $query = KpiWorkAssignmentForm::find()
            ->andFilterWhere(['>=', 'start_date', $start])
            ->andFilterWhere(['<=', 'start_date', $end]);

        // ----- Kiểm tra quyền duyệt -----
        $canApprove = PermissionHelper::check('work-assignment/approve/index');

        if (!Yii::$app->user->isSuperadmin && !$canApprove) {
            $query->andWhere(['staff_id' => Yii::$app->user->id]);
        }

        // --- ActiveDataProvider ---
        $dataProvider = new \yii\data\ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => [
                'defaultOrder' => ['start_date' => SORT_ASC],
            ],
        ]);

        $models = $dataProvider->getModels();

        // -------- Gom nhóm theo ngày --------
        $rowsByDate = [];
        $dates = [];

        foreach ($models as $row) {
            if (!$row) continue;

            $dateKey = Yii::$app->formatter->asDate($row->start_date, 'php:Y-m-d');

            $rowsByDate[$dateKey][] = $row;
            $dates[] = $row->start_date;
        }

        // -------- Tạo HTML bảng --------
        $data = '';
        $stt = 1;

        foreach ($rowsByDate as $dateKey => $items) {
            $displayDate = Yii::$app->formatter->asDate($dateKey, 'php:d/m/Y');
            $rowspan = count($items);
            $first = true;

            foreach ($items as $detail) {
                $data .= "<tr>";

                if ($first) {
                    $data .= "<td rowspan='{$rowspan}'>{$stt}</td>";
                    $data .= "<td rowspan='{$rowspan}'>{$displayDate}</td>";
                    $first = false;
                    $stt++;
                }

                $data .= "
                    <td>{$detail->description}</td>
                    <td>{$detail->staff->name}</td>
                    <td>{$detail->status->name}</td>
                    <td></td>
                ";

                $data .= "</tr>";
            }
        }

        // Tính từ ngày - đến ngày
        if (!empty($dates)) {
            sort($dates);
            $tu_ngay  = Yii::$app->formatter->asDate($dates[0], 'php:d/m/Y');
            $den_ngay = Yii::$app->formatter->asDate(end($dates), 'php:d/m/Y');
        } else {
            $tu_ngay = $den_ngay = '';
        }

        // Render template HTML
        $html = file_get_contents(Yii::getAlias('@app/modules/work_registered/views/report/calendar/pdf-tuan.php'));
        $html = strtr($html, [
            '${data}' => $data,
            '${tu_ngay}' => $tu_ngay,
            '${den_ngay}' => $den_ngay,
        ]);

        // ----- Tạo PDF -----
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'DejaVu Sans',
        ]);

        $mpdf->WriteHTML($html);
        return $mpdf->Output("bao_cao_lich_cong_tac.pdf", "I"); // Mở tab PDF
    }

} // end class
