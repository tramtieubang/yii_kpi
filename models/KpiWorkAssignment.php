<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_assignment".
 *
 * @property int $id ID phân công công việc
 * @property int $work_registered_id ID công việc đăng ký
 * @property int $employee_id ID nhân viên được phân công
 * @property string $assigned_at Thời gian phân công
 * @property int $status Trạng thái công việc (0: chờ làm, 1: đang làm, 2: hoàn thành)
 *
 * @property Employees $employee
 * @property KpiWorkReport[] $kpiWorkReports
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
            [['status'], 'default', 'value' => 0],
            [['work_registered_id', 'employee_id'], 'required'],
            [['work_registered_id', 'employee_id', 'status'], 'integer'],
            [['assigned_at'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['work_registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegistered::class, 'targetAttribute' => ['work_registered_id' => 'id']],
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
            'employee_id' => 'Employee ID',
            'assigned_at' => 'Assigned At',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['id' => 'employee_id']);
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
     * Gets query for [[WorkRegistered]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkRegistered()
    {
        return $this->hasOne(KpiWorkRegistered::class, ['id' => 'work_registered_id']);
    }

}
