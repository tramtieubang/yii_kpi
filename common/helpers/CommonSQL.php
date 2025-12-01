<?php
namespace app\common\helpers;

use app\modules\work_registered\models\KpiWorkRegisteredForm;
use Yii;
use yii\web\NotFoundHttpException;

class CommonSQL
{
   
    public static function approve($id)
    {
        $iStatus = 0;

        $registration = KpiWorkRegisteredForm::findOne($id);

        if (!$registration) {
            throw new NotFoundHttpException("Không tìm thấy công việc đăng ký này.");
        }

        /* // Chỉ cho lãnh đạo duyệt
        if (!Yii::$app->user->can('kpi.approve')) {
            throw new ForbiddenHttpException("Bạn không có quyền duyệt công việc.");
        } */

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // 1️⃣ Cập nhật trạng thái công việc đăng ký
            $registration->status_id = 2; // 2 = Duyệt
            $registration->updated_at = new \yii\db\Expression('NOW()');
            $registration->save(false);

            // 2️⃣ Gán KPI vào bảng KPI thực tế (kpi_work_assignment)
            Yii::$app->db->createCommand()->insert('{{%kpi_work_assignment}}', [
                'work_registered_id' => $registration->id,
                'staff_id' => $registration->staff_id,
                'status_id' => 4, // 4 = Đang thực hiện (theo bảng status)
                'title' => $registration->title,
                'start_date' => $registration->start_date,
                'end_date' => $registration->end_date,
                'color' => '#3788d8', // fallback màu xanh dương
                'assigned_at' => new \yii\db\Expression('NOW()'),
            ])->execute();

            $transaction->commit();

            $iStatus = 1;
            Yii::$app->session->setFlash('success', 'Duyệt công việc thành công, phân công và lưu lịch vào calendar.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Duyệt công việc thất bại: ' . $e->getMessage());
        }

        //return $this->redirect(['index']);
        return  $iStatus;
    }

}