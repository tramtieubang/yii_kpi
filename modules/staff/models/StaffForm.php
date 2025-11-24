<?php

namespace app\modules\staff\models;

use app\models\BusinessFields;
use app\models\Department;
use app\models\KpiKpiEvaluation;
use app\models\KpiSummary;
use app\models\KpiWorkAssignment;
use app\models\KpiWorkRegistered;
use app\models\Positions;
use app\models\Staff;
use app\models\User;
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
class StaffForm extends Staff
{
    public $username;
    public $password;
    public $confirm_password;
    public $status = 1; // default là Hoạt động

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password', 'confirm_password'], 'string', 'max' => 255],
            [['name', 'email'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 50],
            [['status', 'staff_id', 'department_id', 'position_id', 'business_field_id'], 'integer'],
            [['created_at', 'updated_at', 'hire_date'], 'safe'],

            [['name', 'email', 'department_id'], 'required'],

            // Password chỉ required khi tạo mới
            [['password', 'confirm_password'], 'required',
                'when' => function ($model) {
                    return !empty($model->username) && empty($model->staff_id);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#employeesform-username').val().length > 0 && !$('#employeesform-staff_id').val();
                }",
                'message' => 'Bạn phải nhập mật khẩu khi tạo tài khoản mới.'
            ],

            // So khớp confirm_password với password nếu password có giá trị
            ['confirm_password', 'compare', 'compareAttribute' => 'password',
                'message' => "Mật khẩu xác nhận không khớp",
                'skipOnEmpty' => false, // Không bỏ qua nếu confirm_password có giá trị
                'when' => function($model) {
                    return trim($model->password) !== '';
                },
                'whenClient' => "function (attribute, value) {
                    return $('#employeesform-password').val().length > 0;
                }",
            ],

            [['email'], 'unique'],

            [['business_field_id'], 'exist', 'skipOnError' => true,
                'targetClass' => BusinessFields::class,
                'targetAttribute' => ['business_field_id' => 'business_field_id'],
            ],
            [['department_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Department::class,
                'targetAttribute' => ['department_id' => 'department_id'],
            ],
            [['position_id'], 'exist', 'skipOnError' => true,
                'targetClass' => Positions::class,
                'targetAttribute' => ['position_id' => 'position_id'],
            ],
        ];
    }
   
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Tên đăng nhập',
            'staff_id' => 'Mã nhân viên',
            'department_id' => 'Phòng ban',
            'position_id' => 'Chức vụ',
            'business_field_id' => 'Lĩnh vực kinh doanh',
            'name' => 'Họ tên',
            'email' => 'Email',
            'phone' => 'Điện thoại',
            'hire_date' => 'Ngày tuyển dụng',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'staff_id']);
    }


}
