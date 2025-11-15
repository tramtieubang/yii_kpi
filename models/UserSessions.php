<?php

namespace app\models;

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
class UserSessions extends \yii\db\ActiveRecord
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
            'id' => 'ID',
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'ip_address' => 'Ip Address',
            'user_agent' => 'User Agent',
            'device_name' => 'Device Name',
            'login_time' => 'Login Time',
            'last_activity' => 'Last Activity',
            'logout_time' => 'Logout Time',
            'revoked_by_admin' => 'Revoked By Admin',
        ];
    }

}
