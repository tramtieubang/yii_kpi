<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_assignment_history".
 *
 * @property int $id ID lịch sử
 * @property int $assignment_id ID phân công công việc
 * @property int|null $staff_id Nhân viên được phân công
 * @property int|null $status_id Trạng thái công việc
 * @property int|null $kpi_id ID KPI liên quan
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả công việc
 * @property string $start_date Ngày bắt đầu
 * @property string|null $end_date Ngày kết thúc
 * @property string|null $color Màu sắc lịch
 * @property string|null $action_type Loại hành động (create/update/delete/assign/change)
 * @property string|null $assigned_at Ngày phân công
 * @property int|null $updated_by Người thực hiện hành động
 * @property string|null $created_at
 *
 * @property KpiWorkAssignment $assignment
 * @property KpiKpi $kpi
 * @property Staff $staff
 * @property KpiWorkAssignmentStatus $status
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
            [['staff_id', 'status_id', 'kpi_id', 'description', 'end_date', 'updated_by'], 'default', 'value' => null],
            [['color'], 'default', 'value' => '#3788d8'],
            [['action_type'], 'default', 'value' => 'update'],
            [['work_assignment_id', 'title', 'start_date'], 'required'],
            [['assignment_id', 'staff_id', 'status_id', 'kpi_id', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'end_date', 'assigned_at', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['color', 'action_type'], 'string', 'max' => 20],
            [['work_assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignment::class, 'targetAttribute' => ['assignment_id' => 'id']],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::class, 'targetAttribute' => ['staff_id' => 'staff_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignmentStatus::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'assignment_id' => 'Assignment ID',
            'staff_id' => 'Staff ID',
            'status_id' => 'Status ID',
            'kpi_id' => 'Kpi ID',
            'title' => 'Title',
            'description' => 'Description',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'color' => 'Color',
            'action_type' => 'Action Type',
            'assigned_at' => 'Assigned At',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Assignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(KpiWorkAssignment::class, ['id' => 'assignment_id']);
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
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasOne(Staff::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[Status]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStatus()
    {
        return $this->hasOne(KpiWorkAssignmentStatus::class, ['id' => 'status_id']);
    }

}
