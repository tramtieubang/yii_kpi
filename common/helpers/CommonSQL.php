<?php
namespace app\common\helpers;

use app\models\KpiWorkAssignmentHistory;
use app\models\KpiWorkRegisteredHistory;
use app\modules\work_registered\models\KpiWorkRegisteredForm;
use Yii;
use yii\web\NotFoundHttpException;

class CommonSQL
{
    /**
     * Lưu lịch sử công việc đăng ký
     * @param \app\models\KpiWorkRegistered $oldModel  Bản ghi cũ (trước khi sửa), null nếu create
     * @param \app\models\KpiWorkRegistered $newModel  Bản ghi mới (sau khi thay đổi)
     * @param string $action  Loại hành động: create/update/delete
     * @return bool
     */
    public static function saveRegisteredHistory($oldModel, $newModel, $action = 'update', $extra = [])
    {
        $history = new KpiWorkRegisteredHistory();

        // ====== Thông tin cơ bản ======
        $history->work_registered_id = $newModel->id;
        $history->staff_id = $newModel->staff_id;
        $history->kpi_id = $newModel->kpi_id;

        // ====== Snapshot dữ liệu ======
        $history->title = $newModel->title;
        $history->description = $newModel->description;
        $history->start_date = $newModel->start_date;
        $history->end_date = $newModel->end_date;
        $history->color = $newModel->color ?? ($newModel->status->color ?? '#3788d8');

        // ====== Old / New status ======
        $history->old_status = $oldModel ? $oldModel->status_id : null;
        $history->new_status = $newModel->status_id;

        // ====== JSON dữ liệu cũ ======
        $history->old_data = $oldModel
            ? json_encode($oldModel->getAttributes(), JSON_UNESCAPED_UNICODE)
            : null;

        // ====== JSON dữ liệu mới ======
        $newData = $newModel->getAttributes();

        // Gộp thêm dữ liệu bổ sung vào JSON nếu có
        if (!empty($extra) && is_array($extra)) {
            $newData = array_merge($newData, $extra);
        }

        $history->new_data = json_encode($newData, JSON_UNESCAPED_UNICODE);

        // ====== Gán reject_reason nếu có ======
        if (isset($extra['reject_reason'])) {
            $history->reject_reason = $extra['reject_reason'];
        }

        // ====== Action ======
        $history->action_type = $action;

        // ====== User thao tác ======
        $history->updated_by = Yii::$app->user->id ?? null;

        return $history->save(false);
    }

    public static function saveAssignmentHistory($oldModel, $newModel, $action = 'update')
    {
        $history = new KpiWorkAssignmentHistory();

        // ID bản chính
        $history->work_assignment_id = $newModel->id;

        // Snapshot từ bản mới (để xem công việc ở thời điểm hiện tại)
        $history->staff_id = $newModel->staff_id;
        $history->kpi_id = $newModel->kpi_id;

        $history->title = $newModel->title;
        $history->description = $newModel->description;

        $history->start_date = $newModel->start_date;
        $history->end_date = $newModel->end_date;
        $history->color = $newModel->color;

         // Trạng thái cũ / mới
        $history->old_status = $oldModel ? $oldModel->status_id : null;
        $history->new_status = $newModel->status_id;

        // JSON dữ liệu cũ và mới
        $history->old_data = $oldModel ? json_encode($oldModel->getAttributes(), JSON_UNESCAPED_UNICODE) : null;
        $history->new_data = json_encode($newModel->getAttributes(), JSON_UNESCAPED_UNICODE);
        
        // loại hành động
        $history->action_type = $action;

        // người thao tác
        $history->updated_by = Yii::$app->user->id ?? null;

        // Lưu
        return $history->save(false);
    }
   
    public static function approve($registration)
    {
        $iStatus = 0;

        // Nếu truyền vào là ID → lấy model
        if (is_numeric($registration)) {
            $registration = KpiWorkRegisteredForm::findOne($registration);
        }

        // Kiểm tra model hợp lệ
        if (!$registration instanceof KpiWorkRegisteredForm) {
            throw new NotFoundHttpException("Không tìm thấy công việc đăng ký này.");
        }

        /* // Chỉ cho lãnh đạo duyệt
        if (!Yii::$app->user->can('kpi.approve')) {
            throw new ForbiddenHttpException("Bạn không có quyền duyệt công việc.");
        } */

        $transaction = Yii::$app->db->beginTransaction();

        try {

            // 2️⃣ Gán KPI vào bảng KPI thực tế (kpi_work_assignment)
            Yii::$app->db->createCommand()->insert('{{%kpi_work_assignment}}', [
                'work_registered_id' => $registration->id,
                'staff_id' => $registration->staff_id,
                'status_id' => 4, // 4 = Đang thực hiện (theo bảng status)
                'title' => $registration->title,
                'description' => $registration->description,
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