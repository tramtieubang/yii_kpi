<?php

namespace app\modules\user_management\session_manager\models;

use app\models\UserSessions;
use Yii;

/**
 * This is the model class for table "user_sessions".
 *
 * @property int $id
 * @property int $user_id
 * @property string $session_id
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $device_name
 * @property string $login_time
 * @property string|null $last_activity
 * @property string|null $logout_time
 * @property int|null $revoked_by_admin
 */
class UserSessionsForm extends UserSessions
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_sessions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ip_address', 'user_agent', 'device_name', 'last_activity', 'logout_time'], 'default', 'value' => null],
            [['revoked_by_admin'], 'default', 'value' => 0],
            [['user_id', 'session_id', 'login_time'], 'required'],
            [['user_id', 'revoked_by_admin'], 'integer'],
            [['login_time', 'last_activity', 'logout_time'], 'safe'],
            [['session_id'], 'string', 'max' => 64],
            [['ip_address'], 'string', 'max' => 45],
            [['user_agent'], 'string', 'max' => 255],
            [['device_name'], 'string', 'max' => 100],
            [['session_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
            'id' => 'Mã phiên',
            'user_id' => 'Người dùng',
            'session_id' => 'Mã phiên đăng nhập',
            'ip_address' => 'Địa chỉ IP',
            'user_agent' => 'Trình duyệt / Thiết bị',
            'device_name' => 'Tên thiết bị',
            'login_time' => 'Thời gian đăng nhập',
            'last_activity' => 'Hoạt động gần nhất',
            'logout_time' => 'Thời gian đăng xuất',
            'revoked_by_admin' => 'Bị quản trị viên thu hồi',
        ];

    }

    public function getUser()
    {
        return $this->hasOne(\app\models\User::class, ['id' => 'user_id']);
    }


    public function getStatusLabel()
    {
        if ($this->revoked_by_admin) return 'Đã bị admin thu hồi';
        if ($this->logout_time) return 'Đã đăng xuất';
        return 'Đang hoạt động';
    }

    public function getIs_Active()
    {
        return $this->logout_time === null && !$this->revoked_by_admin;
    }
    

}
