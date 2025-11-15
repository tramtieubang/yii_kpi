<?php

namespace app\custom;

use app\modules\user_management\user\models\User;
use Yii;

class PermissionHelper
{
    public static function check($permission)
    {
        if (Yii::$app->user->isSuperadmin) {
            return true;
        } else {
            return User::canRoute($permission);
        }
    }
}
