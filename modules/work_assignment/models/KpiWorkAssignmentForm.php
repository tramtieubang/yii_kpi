<?php

namespace app\modules\work_assignment\models;

use app\models\KpiWorkAssignment;
use Yii;

/**
 * This is the model class for table "kpi_work_assignment".
 *
 * @property int $id ID phân công
 * @property int $work_registered_id ID công việc đăng ký
 * @property int $staff_id ID nhân viên được phân công
 * @property int $status_id Trạng thái phân công
 * @property string $start_date Ngày bắt đầu
 * @property string $end_date Ngày kết thúc
 * @property string $title Tiêu đề công việc
 * @property string|null $color Màu lịch
 * @property string|null $assigned_at Ngày phân công
 *
 * @property KpiWorkRelation[] $kpiWorkRelations
 * @property KpiWorkReport[] $kpiWorkReports
 * @property KpiWorkResult[] $kpiWorkResults
 * @property Staff $staff
 * @property KpiWorkAssignmentStatus $status
 * @property KpiWorkRegistered $workRegistered
 */
class KpiWorkAssignmentForm extends KpiWorkAssignment
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_assignment';
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
            'id' => 'Mã phân công',
            'work_registered_id' => 'Công việc đăng ký',
            'staff_id' => 'Nhân viên được phân công',
            'status_id' => 'Trạng thái phân công',
            'kpi_id' => 'KPI liên quan',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'title' => 'Tiêu đề công việc',
            'description' => 'Mô tả công việc',
            'color' => 'Màu hiển thị',
            'assigned_at' => 'Ngày phân công',
        ];

    }

    // 🔥 Thêm relation getJobs()
    public function getJobs() {
        return $this->hasMany(KpiWorkAssignmentForm::class, ['staff_id'=>'staff_id']); // chỉ lấy công việc con
    }

}
