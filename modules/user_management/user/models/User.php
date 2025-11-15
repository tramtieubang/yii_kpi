<?php

namespace app\modules\user_management\user\models;

use yii\helpers\ArrayHelper;

class User extends \webvimark\modules\UserManagement\models\User
{

    public function attributeLabels()
    {
        return [
            'id'                 => 'ID',
            'username'           => 'Tên tài khoản',
            'superadmin'         => 'Là super admin',
            'confirmation_token' => 'Confirmation Token',
            'registration_ip'    => 'Registration IP',
            'bind_to_ip'         => 'Liên kết với địa chỉ IP',
            'status'             => 'Trạng thái',
            'gridRoleSearch'     => 'Roles',
            'created_at'         => 'Created',
            'updated_at'         => 'Updated',
            'password'           => 'Mật khẩu',
            'repeat_password'    => 'Nhắc lại mật khẩu',
            'email_confirmed'    => 'E-mail confirmed',
            'email'              => 'Tài khoản Email',
        ];
    }

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['password', 'repeat_password'], 'string', 'min' => 8],
            ]
        );
    }

    public function getUserRoleName()
    {
        return 'user_' . $this->id . '_';  // dang user_9_
    }

    
}