<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "staff".
 *
 * @property int $staff_id ID nhân viên
 * @property int $department_id ID phòng ban
 * @property int|null $position_id ID chức vụ
 * @property int|null $business_field_id ID lĩnh vực kinh doanh
 * @property string $name Họ tên nhân viên
 * @property string $email Email nhân viên
 * @property string|null $phone Số điện thoại
 * @property string|null $hire_date Ngày tuyển dụng
 * @property string|null $created_at Thời gian tạo
 * @property string|null $updated_at Thời gian cập nhật
 *
 * @property BusinessFields $businessField
 * @property Department $department
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiSummary[] $kpiSummaries
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 * @property Positions $position
 */
class Staff extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'staff';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id', 'business_field_id', 'phone', 'hire_date'], 'default', 'value' => null],
            [['department_id', 'name', 'email'], 'required'],
            [['department_id', 'position_id', 'business_field_id'], 'integer'],
            [['hire_date', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 191],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['business_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessFields::class, 'targetAttribute' => ['business_field_id' => 'business_field_id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Department::class, 'targetAttribute' => ['department_id' => 'department_id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Positions::class, 'targetAttribute' => ['position_id' => 'position_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'staff_id' => 'Staff ID',
            'department_id' => 'Department ID',
            'position_id' => 'Position ID',
            'business_field_id' => 'Business Field ID',
            'name' => 'Name',
            'email' => 'Email',
            'phone' => 'Phone',
            'hire_date' => 'Hire Date',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[BusinessField]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessField()
    {
        return $this->hasOne(BusinessFields::class, ['business_field_id' => 'business_field_id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Department::class, ['department_id' => 'department_id']);
    }

    /**
     * Gets query for [[KpiKpiEvaluations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiKpiEvaluations()
    {
        return $this->hasMany(KpiKpiEvaluation::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[KpiSummaries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiSummaries()
    {
        return $this->hasMany(KpiSummary::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[KpiWorkAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkAssignments()
    {
        return $this->hasMany(KpiWorkAssignment::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[KpiWorkRegistereds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRegistereds()
    {
        return $this->hasMany(KpiWorkRegistered::class, ['staff_id' => 'staff_id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Positions::class, ['position_id' => 'position_id']);
    }

}
