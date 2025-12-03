<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_assignment_history".
 *
 * @property int $id ID bản ghi lịch sử
 * @property int $work_assignment_id ID công việc phân công gốc
 * @property int|null $staff_id ID nhân viên được phân công
 * @property int|null $kpi_id ID KPI liên quan
 * @property string $title Tiêu đề công việc tại thời điểm thay đổi
 * @property string|null $description Mô tả công việc tại thời điểm thay đổi
 * @property string $start_date Ngày bắt đầu
 * @property string|null $end_date Ngày kết thúc
 * @property string|null $color Màu lịch
 * @property int|null $old_status Trạng thái cũ
 * @property int|null $new_status Trạng thái mới
 * @property string|null $old_data Dữ liệu cũ dạng JSON
 * @property string|null $new_data Dữ liệu mới dạng JSON
 * @property string|null $action_type Loại hành động
 * @property int|null $updated_by ID người thực hiện hành động
 * @property string|null $assigned_at Ngày thực hiện phân công / thay đổi
 * @property string|null $created_at Thời gian tạo bản ghi lịch sử
 *
 * @property KpiKpi $kpi
 * @property KpiWorkAssignmentStatus $newStatus
 * @property KpiWorkAssignmentStatus $oldStatus
 * @property Staff $staff
 * @property KpiWorkAssignment $workAssignment
 */
class KpiWorkAssignmentHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_assignment_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['staff_id', 'kpi_id', 'description', 'end_date', 'old_status', 'new_status', 'old_data', 'new_data', 'updated_by'], 'default', 'value' => null],
            [['color'], 'default', 'value' => '#3788d8'],
            [['action_type'], 'default', 'value' => 'update'],
            [['work_assignment_id', 'title', 'start_date'], 'required'],
            [['work_assignment_id', 'staff_id', 'kpi_id', 'old_status', 'new_status', 'updated_by'], 'integer'],
            [['description', 'old_data', 'new_data'], 'string'],
            [['start_date', 'end_date', 'assigned_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['color', 'action_type'], 'string', 'max' => 20],
            [['new_status'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignmentStatus::class, 'targetAttribute' => ['new_status' => 'id']],
            [['old_status'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignmentStatus::class, 'targetAttribute' => ['old_status' => 'id']],
            [['work_assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignment::class, 'targetAttribute' => ['work_assignment_id' => 'id']],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::class, 'targetAttribute' => ['staff_id' => 'staff_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_assignment_id' => 'Work Assignment ID',
            'staff_id' => 'Staff ID',
            'kpi_id' => 'Kpi ID',
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'color' => 'Color',
            'old_status' => 'Old Status',
            'new_status' => 'New Status',
            'old_data' => 'Old Data',
            'new_data' => 'New Data',
            'action_type' => 'Action Type',
            'updated_by' => 'Updated By',
            'assigned_at' => 'Assigned At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Kpi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpi()
    {
        return $this->hasOne(KpiKpi::class, ['id' => 'kpi_id']);
    }

    /**
     * Gets query for [[NewStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewStatus()
    {
        return $this->hasOne(KpiWorkAssignmentStatus::class, ['id' => 'new_status']);
    }

    /**
     * Gets query for [[OldStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOldStatus()
    {
        return $this->hasOne(KpiWorkAssignmentStatus::class, ['id' => 'old_status']);
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[WorkAssignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkAssignment()
    {
        return $this->hasOne(KpiWorkAssignment::class, ['id' => 'work_assignment_id']);
    }

}
