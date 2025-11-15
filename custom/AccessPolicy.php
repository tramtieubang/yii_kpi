<?php

namespace app\custom;

use app\modules\staff\models\StaffForm;
use Yii;

class AccessPolicy
{
    public static function can($model): bool
    {
        return self::isOwner($model) || self::isAdmin();
    }

    public static function canView($model): bool
    {
        return self::isOwner($model) || self::isAdmin();
    }

    public static function canUpdate($model): bool
    {
        return self::isOwner($model) || self::isAdmin();
    }

    public static function canDelete($model): bool
    {
        return self::isOwner($model) || self::isAdmin();
    }

    public static function canIndex(): bool
    {
        return true;
    }

    public static function filter($query)
    {
        $tableName = $query->modelClass::tableName();

        if (!self::isAdmin()) {
            $query->andWhere([$tableName . '.staff_id' => Yii::$app->user->id]);
        }

        return $query;
    }

    public static function authorize(string $action,  $model = null): bool
    {
        switch ($action) {
            case 'view':
                return $model ? self::canView($model) : false;
            case 'update':
                return $model ? self::canUpdate($model) : false;
            case 'delete':
                return $model ? self::canDelete($model) : false;
            case 'index':
                return self::canIndex();
            default:
                return false;
        }
    }

    public static function isOwner($model): bool
    {
        return $model->staff_id == Yii::$app->user->id;
    }

    public static function isAdmin(): bool
    {
        if (Yii::$app->user->isSuperadmin) {
            return true;
        } else {
            $staff = StaffForm::findOne(Yii::$app->user->id);
            // Chức vụ: Không phải nhân viên
            if (!empty($staff->position) && $staff->position !== 'staff') {
                return true;
            } else {
                return false;
            }
        }
    }
}
