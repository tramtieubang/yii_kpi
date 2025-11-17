<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property int $id ID nhân viên
 * @property int $user_id ID user trong bảng user
 * @property int $department_id ID phòng ban
 * @property int|null $position_id ID chức vụ
 * @property int|null $business_field_id ID lĩnh vực kinh doanh
 * @property string $name Họ tên nhân viên
 * @property string $email Email nhân viên
 * @property string|null $phone Số điện thoại
 * @property string|null $hire_date Ngày tuyển dụng
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property BusinessFields $businessField
 * @property Departments $department
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiSummary[] $kpiSummaries
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 * @property Positions $position
 * @property User $user
 */
class Employees extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['position_id', 'business_field_id', 'phone', 'hire_date'], 'default', 'value' => null],
            [['user_id', 'department_id', 'name', 'email'], 'required'],
            [['user_id', 'department_id', 'position_id', 'business_field_id'], 'integer'],
            [['hire_date', 'created_at', 'updated_at'], 'safe'],
            [['name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['business_field_id'], 'exist', 'skipOnError' => true, 'targetClass' => BusinessFields::class, 'targetAttribute' => ['business_field_id' => 'id']],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::class, 'targetAttribute' => ['department_id' => 'id']],
            [['position_id'], 'exist', 'skipOnError' => true, 'targetClass' => Positions::class, 'targetAttribute' => ['position_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
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
        return $this->hasOne(BusinessFields::class, ['id' => 'business_field_id']);
    }

    /**
     * Gets query for [[Department]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartment()
    {
        return $this->hasOne(Departments::class, ['id' => 'department_id']);
    }

    /**
     * Gets query for [[KpiKpiEvaluations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiKpiEvaluations()
    {
        return $this->hasMany(KpiKpiEvaluation::class, ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[KpiSummaries]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiSummaries()
    {
        return $this->hasMany(KpiSummary::class, ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkAssignments()
    {
        return $this->hasMany(KpiWorkAssignment::class, ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkRegistereds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRegistereds()
    {
        return $this->hasMany(KpiWorkRegistered::class, ['employee_id' => 'id']);
    }

    /**
     * Gets query for [[Position]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPosition()
    {
        return $this->hasOne(Positions::class, ['id' => 'position_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
