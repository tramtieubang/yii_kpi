<?php

namespace app\modules\user_management\user\models;

use app\models\AuthAssignment;
use app\models\AuthItem;
use app\models\UserVisitLog;
use app\modules\user_management\user\models\User;
use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string|null $confirmation_token
 * @property int $status
 * @property int|null $superadmin
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $registration_ip
 * @property string|null $bind_to_ip
 * @property string|null $email
 * @property int $email_confirmed
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property UserVisitLog[] $userVisitLogs
 */
class UserForm extends User
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            // Bắt buộc nhập các trường cơ bản
            [['username', 'status'], 'required', 'message' => 'Trường này không được để trống.'],

            // Kiểm tra username trùng lặp
            ['username', 'unique', 'targetClass' => self::class, 'message' => 'Tên đăng nhập đã tồn tại.'],

            // Khi thêm mới: bắt buộc nhập mật khẩu
            [['password', 'repeat_password'], 'required', 'on' => 'create', 'message' => 'Vui lòng nhập {attribute}.'],

            // Khi cập nhật: bắt buộc nếu nhập một trong hai ô
            [['password', 'repeat_password'], 'required',
                'when' => function ($model) {
                    return !empty($model->password) || !empty($model->repeat_password);
                },
                'whenClient' => "function (attribute, value) {
                    return $('#userform-password').val() !== '' || $('#userform-repeat_password').val() !== '';
                }",
                'on' => 'update',
                'message' => 'Vui lòng nhập {attribute}.'
            ],

            // Hai mật khẩu phải khớp
            ['repeat_password', 'compare', 'compareAttribute' => 'password', 'message' => 'Mật khẩu nhập lại không khớp.'],

            // Giới hạn độ dài
            [['username', 'password', 'repeat_password', 'email', 'bind_to_ip'], 'string', 'max' => 255],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        $scenarios['create'] = ['username', 'password', 'repeat_password', 'status', 'email', 'bind_to_ip'];
        $scenarios['update'] = ['username', 'status', 'email', 'bind_to_ip', 'password', 'repeat_password'];

        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [                     
           'id' => 'Mã số',
            'username' => 'Tên tài khoản',
            'auth_key' => 'Khóa xác thực',
            'password_hash' => 'Mật khẩu (mã hóa)',
            'confirmation_token' => 'Mã xác nhận',
            'status' => 'Trạng thái',
            'superadmin' => 'Quản trị tối cao',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
            'registration_ip' => 'Địa chỉ IP đăng ký',
            'bind_to_ip' => 'Ràng buộc IP',
            'email' => 'Địa chỉ Email',
            'email_confirmed' => 'Xác nhận Email',
            'Grid Role Search' => 'Vai trò',
        ];
    }

    /**
     * Gets query for [[AuthAssignments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[ItemNames]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItemNames()
    {
        return $this->hasMany(AuthItem::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    /**
     * Gets query for [[UserVisitLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserVisitLogs()
    {
        return $this->hasMany(UserVisitLog::class, ['user_id' => 'id']);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
}
