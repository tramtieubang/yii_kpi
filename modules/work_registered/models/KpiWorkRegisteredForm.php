<?php

namespace app\modules\work_registered\models;

use app\models\KpiKpi;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use app\models\KpiWorkRegisteredHistory;
use app\models\KpiWorkRegisteredStatus;
use app\models\KpiWorkReport;
use app\models\Staff;
use Yii;

/**
 * This is the model class for table "kpi_work_registered".
 *
 * @property int $id
 * @property int $staff_id ID nhân viên
 * @property int $kpi_id ID KPI
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả
 * @property int $status_id ID trạng thái
 * @property string $start_date
 * @property string|null $end_date
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Staff $staff
 * @property KpiKpi $kpi
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 * @property KpiWorkRegisteredHistory[] $kpiWorkRegisteredHistories
 * @property KpiWorkRegisteredStatus $status
 */
class KpiWorkRegisteredForm extends KpiWorkRegistered
{
     // Thuộc tính ảo để phân biệt nhân viên (parent) và công việc (child)
    public $is_parent;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_registered';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {       
        return [
            'id' => 'Mã',
            'staff_id' => 'Nhân viên',
            'kpi_id' => 'Chỉ tiêu KPI',
            'title' => 'Tiêu đề công việc',
            'description' => 'Mô tả',
            'status_id' => 'Trạng thái',
            'color' => 'Màu hiển thị',
            'start_date' => 'Ngày bắt đầu',
            'end_date' => 'Ngày kết thúc',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

     // Relation jobs
    public function getJobs() {
        return $this->hasMany(KpiWorkRegisteredForm::class, ['staff_id'=>'staff_id']); // chỉ lấy công việc con
    }

    public function hasAnyReport()
    {
        return KpiWorkReport::find()
            ->innerJoin('kpi_work_assignment a', 'a.id = kpi_work_report.work_assignment_id')
            ->where(['a.work_registered_id' => $this->id])
            ->exists();
    }

}
