<?php

namespace app\modules\work_registered\models;

use app\models\Employees;
use app\models\KpiKpi;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use Yii;

/**
 * This is the model class for table "kpi_work_registered".
 *
 * @property int $id ID đăng ký công việc
 * @property int $employee_id ID nhân viên đăng ký
 * @property int $kpi_id ID KPI liên quan
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả công việc
 * @property int $status Trạng thái (0: chờ duyệt, 1: duyệt, 2: từ chối)
 * @property string $date_start Ngày bắt đầu
 * @property string|null $date_end Ngày kết thúc dự kiến
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property Employees $employee
 * @property KpiKpi $kpi
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 */
class KpiWorkRegisteredForm extends KpiWorkRegistered
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
            [['description', 'date_end'], 'default', 'value' => null],
            [['status'], 'default', 'value' => 0],
            [['employee_id', 'kpi_id', 'title', 'date_start'], 'required'],
            [['employee_id', 'kpi_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
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
            'kpi_id' => 'Kpi ID',
            'title' => 'Title',
            'description' => 'Description',
            'status' => 'Status',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
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

}
