<?php

namespace app\modules\employees\models;

use app\models\Departments;
use app\models\Employees;
use app\models\KpiKpiEvaluation;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use app\models\User;
use Yii;

/**
 * This is the model class for table "employees".
 *
 * @property int $id ID nhân viên
 * @property int $user_id ID user trong module quản lý người dùng Yii2
 * @property int $department_id ID phòng ban của nhân viên
 * @property string $name Họ tên nhân viên
 * @property string $email Email nhân viên
 * @property string|null $phone Số điện thoại
 * @property string|null $position Chức vụ
 * @property string|null $hire_date Ngày tuyển dụng
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property Departments $department
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiWorkAssignment[] $kpiWorkAssignments
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 * @property User $user
 */
class EmployeesForm extends Employees
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
            [['phone', 'position', 'hire_date'], 'default', 'value' => null],
            [['user_id', 'department_id', 'name', 'email'], 'required'],
            [['user_id', 'department_id'], 'integer'],
            [['hire_date', 'created_at', 'updated_at'], 'safe'],
            [['name', 'email', 'position'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['email'], 'unique'],
            [['department_id'], 'exist', 'skipOnError' => true, 'targetClass' => Departments::class, 'targetAttribute' => ['department_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Mã',
            'user_id' => 'Tài khoản người dùng',
            'department_id' => 'Phòng/Ban',
            'name' => 'Họ và tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'position' => 'Chức vụ',
            'hire_date' => 'Ngày tuyển dụng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];

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
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
