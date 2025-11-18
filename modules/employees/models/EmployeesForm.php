<?php

namespace app\modules\employees\models;

use app\models\BusinessFields;
use app\models\Departments;
use app\models\Employees;
use app\models\KpiKpiEvaluation;
use app\models\KpiSummary;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use app\models\Positions;
use app\models\User;
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
class EmployeesForm extends Employees
{

     // Nếu EmployeesForm là ActiveRecord, bảng employee không có cột username
    // thì tạo virtual attribute
    public $username;
    public $password;
    public $confirm_password;
    public $status = 1; // default là Hoạt động

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
            [['username', 'password', 'confirm_password'], 'string', 'max' => 255],

            // Password chỉ required khi tạo user mới
            [['password', 'confirm_password'], 'required',
                'when' => function ($model) {
                    // Chỉ bắt nhập password khi tạo user mới (user_id chưa có) và có username
                    return !empty($model->username) && empty($model->user_id);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#employeesform-username').val().length > 0 && !$('#employeesform-user_id').val();
                }",
                'message' => 'Bạn phải nhập mật khẩu khi tạo tài khoản mới.'
            ],

            // So khớp confirm_password với password, nhưng chỉ validate khi password có giá trị
            ['confirm_password', 'compare', 'compareAttribute' => 'password',
                'message' => "Mật khẩu xác nhận không khớp",
                'when' => function($model) {
                    return !empty($model->password);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#employeesform-password').val().length > 0;
                }",
            ],

            [['status'], 'integer'], // validate kiểu int

            [['position_id', 'business_field_id', 'phone', 'hire_date'], 'default', 'value' => null],
            [['department_id', 'name', 'email'], 'required'],
            [['user_id', 'department_id', 'position_id', 'business_field_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
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
            'username' => 'Tên đăng nhập',
            'password' => 'Mật khẩu',
            'confirm_password' => 'Xác nhận mật khẩu',
            'id' => 'Mã',
            'user_id' => 'Tài khoản người dùng',
            'department_id' => 'Phòng ban',
            'position_id' => 'Chức vụ',
            'business_field_id' => 'Lĩnh vực kinh doanh',
            'name' => 'Họ và tên',
            'email' => 'Email',
            'phone' => 'Số điện thoại',
            'hire_date' => 'Ngày tuyển dụng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
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
