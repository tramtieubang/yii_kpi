<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_registered".
 *
 * @property int $id ID công việc đăng ký
 * @property int $staff_id ID nhân viên
 * @property int|null $kpi_id ID KPI liên quan
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả công việc
 * @property int $status_id Trạng thái công việc
 * @property string|null $color Màu lịch
 * @property string $start_date Ngày bắt đầu công việc
 * @property string|null $end_date Ngày kết thúc công việc
 * @property string|null $created_at Ngày tạo
 * @property string|null $updated_at Ngày cập nhật
 *
 * @property KpiKpi $kpi
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 * @property KpiWorkRegisteredHistory[] $kpiWorkRegisteredHistories
 * @property Staff $staff
 * @property KpiWorkRegisteredStatus $status
 */
class KpiWorkRegistered extends \yii\db\ActiveRecord
{


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
    public function rules()
    {
        return [
            [['kpi_id', 'description', 'end_date'], 'default', 'value' => null],
            [['status_id'], 'default', 'value' => 1],
            [['color'], 'default', 'value' => '#3788d8'],
            [['staff_id', 'title', 'start_date'], 'required'],
            [['staff_id', 'kpi_id', 'status_id'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
            [['staff_id'], 'exist', 'skipOnError' => true, 'targetClass' => Staff::class, 'targetAttribute' => ['staff_id' => 'staff_id']],
            [['status_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegisteredStatus::class, 'targetAttribute' => ['status_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'staff_id' => 'Staff ID',
            'kpi_id' => 'Kpi ID',
            'title' => 'Title',
            'description' => 'Description',
            'status_id' => 'Status ID',
            'color' => 'Color',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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
     * Gets query for [[KpiWorkAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkAssignments()
    {
        return $this->hasMany(KpiWorkAssignment::class, ['work_registered_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkRegisteredHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRegisteredHistories()
    {
        return $this->hasMany(KpiWorkRegisteredHistory::class, ['work_registered_id' => 'id']);
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
        return $this->hasOne(KpiWorkRegisteredStatus::class, ['id' => 'status_id']);
    }

}
