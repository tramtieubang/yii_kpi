<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_registered_history".
 *
 * @property int $id ID bản ghi lịch sử
 * @property int $work_registered_id ID công việc đăng ký gốc
 * @property int $staff_id ID nhân viên
 * @property int|null $kpi_id ID KPI liên quan
 * @property string $title Tiêu đề công việc tại thời điểm thay đổi
 * @property string|null $description Mô tả công việc tại thời điểm thay đổi
 * @property string $start_date Ngày bắt đầu công việc tại thời điểm thay đổi
 * @property string|null $end_date Ngày kết thúc công việc tại thời điểm thay đổi
 * @property string|null $color Màu sắc công việc
 * @property int|null $old_status Trạng thái cũ
 * @property int|null $new_status Trạng thái mới
 * @property string|null $old_data Dữ liệu cũ dạng JSON
 * @property string|null $new_data Dữ liệu mới dạng JSON
 * @property string|null $action_type Loại hành động
 * @property int|null $updated_by ID người thực hiện hành động
 * @property string|null $created_at Thời gian tạo bản ghi lịch sử
 *
 * @property KpiKpi $kpi
 * @property KpiWorkRegisteredStatus $newStatus
 * @property KpiWorkRegisteredStatus $oldStatus
 * @property Staff $staff
 * @property KpiWorkRegistered $workRegistered
 */
class KpiWorkRegisteredHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_registered_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['kpi_id', 'description', 'end_date', 'old_status', 'new_status', 'old_data', 'new_data', 'updated_by'], 'default', 'value' => null],
            [['color'], 'default', 'value' => '#3788d8'],
            [['action_type'], 'default', 'value' => 'update'],
            [['work_registered_id', 'staff_id', 'title', 'start_date'], 'required'],
            [['work_registered_id', 'staff_id', 'kpi_id', 'old_status', 'new_status', 'updated_by'], 'integer'],
            [['description', 'old_data', 'new_data'], 'string'],
            [['start_date', 'end_date', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['color', 'action_type'], 'string', 'max' => 20],
            [['new_status'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegisteredStatus::class, 'targetAttribute' => ['new_status' => 'id']],
            [['old_status'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegisteredStatus::class, 'targetAttribute' => ['old_status' => 'id']],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
            [['work_registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegistered::class, 'targetAttribute' => ['work_registered_id' => 'id']],
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
            'work_registered_id' => 'Work Registered ID',
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
        return $this->hasOne(KpiWorkRegisteredStatus::class, ['id' => 'new_status']);
    }

    /**
     * Gets query for [[OldStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOldStatus()
    {
        return $this->hasOne(KpiWorkRegisteredStatus::class, ['id' => 'old_status']);
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
     * Gets query for [[WorkRegistered]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkRegistered()
    {
        return $this->hasOne(KpiWorkRegistered::class, ['id' => 'work_registered_id']);
    }

}
