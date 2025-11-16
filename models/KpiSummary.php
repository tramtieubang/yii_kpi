<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_summary".
 *
 * @property int $id
 * @property int $employee_id ID nhân viên
 * @property int|null $total_registered Tổng công việc đăng ký
 * @property int|null $total_assigned Tổng công việc phân công
 * @property int|null $total_completed Tổng công việc hoàn thành
 * @property float|null $average_score Điểm KPI trung bình
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Employees $employee
 */
class KpiSummary extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_summary';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['total_completed'], 'default', 'value' => 0],
            [['average_score'], 'default', 'value' => 0.00],
            [['employee_id'], 'required'],
            [['employee_id', 'total_registered', 'total_assigned', 'total_completed'], 'integer'],
            [['average_score'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'total_registered' => 'Total Registered',
            'total_assigned' => 'Total Assigned',
            'total_completed' => 'Total Completed',
            'average_score' => 'Average Score',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

}
