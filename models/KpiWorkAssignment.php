<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_assignment".
 *
 * @property int $id ID phân công
 * @property int $work_registered_id ID công việc đăng ký
 * @property int $staff_id ID nhân viên được phân công
 * @property int $status_id Trạng thái phân công
 * @property int|null $kpi_id ID KPI liên quan
 * @property string $start_date Ngày bắt đầu
 * @property string $end_date Ngày kết thúc
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả công việc
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
class KpiWorkAssignment extends \yii\db\ActiveRecord
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
    public function rules()
    {
        return [
            [['work_registered_id','kpi_id', 'description'], 'default', 'value' => null],
            [['status_id'], 'default', 'value' => 1],
            [['color'], 'default', 'value' => '#3788d8'],
            [['staff_id', 'start_date', 'end_date', 'title'], 'required'],
            [['work_registered_id', 'staff_id', 'status_id', 'kpi_id'], 'integer'],
            [['start_date', 'end_date', 'assigned_at'], 'safe'],
            [['description'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
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
            'work_registered_id' => 'Work Registered ID',
            'staff_id' => 'Staff ID',
            'status_id' => 'Status ID',
            'kpi_id' => 'Kpi ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'title' => 'Title',
            'description' => 'Description',
            'color' => 'Color',
            'assigned_at' => 'Assigned At',
        ];
    }

    public function getKpi()
    {
        return $this->hasOne(KpiKpi::class, ['id' => 'kpi_id']);
    }
    
    /**
     * Gets query for [[KpiWorkRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRelations()
    {
        return $this->hasMany(KpiWorkRelation::class, ['assignment_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkReports]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkReports()
    {
        return $this->hasMany(KpiWorkReport::class, ['work_assignment_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkResults]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkResults()
    {
        return $this->hasMany(KpiWorkResult::class, ['work_assignment_id' => 'id']);
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

    /**
     * Gets query for [[WorkRegistered]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkRegistered()
    {
        return $this->hasOne(KpiWorkRegistered::class, ['id' => 'work_registered_id']);
    }

    public function getKpiWorkAssignmentHistories()
    {
        return $this->hasMany(KpiWorkAssignmentHistory::class, ['work_assignment_id' => 'id']);
    }

}
