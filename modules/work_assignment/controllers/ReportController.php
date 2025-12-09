<?php
namespace app\controllers;

use app\models\KpiWorkAssignment;
use Yii;
use yii\web\Controller;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use kartik\mpdf\Pdf;

class ReportController extends Controller
{
    public function actionIndex()
    {
        $models = KpiWorkAssignment::find()->all();
        return $this->render('index', [
            'models' => $models
        ]);
    }

    // ----------------------- PDF -----------------------
    public function actionPdf()
    {
        $models = KpiWorkAssignment::find()->all();

        $content = $this->renderPartial('report', [
            'models' => $models,
        ]);

        $pdf = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'content' => $content,
            'methods' => [
                'SetHeader' => ['TỈNH UỶ VĨNH LONG'],
                'SetFooter' => ['{PAGENO}'],
            ],
        ]);

        return $pdf->render();
    }

    // ----------------------- WORD -----------------------
    public function actionWord()
    {
        $models = KpiWorkAssignment::find()->all();

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
    public function actionExcel()
    {
        $models = KpiWorkAssignment::find()->all();

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
}
