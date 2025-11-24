<?php

namespace app\modules\department\models;

use app\models\Department;
use app\models\Employees;
use Yii;

/**
 * This is the model class for table "departments".
 *
 * @property int $id ID phòng ban
 * @property string $code Mã phòng ban
 * @property string $name Tên phòng ban
 * @property string|null $description Mô tả phòng ban
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property Employees[] $employees
 */
class DepartmentForm extends Department
{
     /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'department';
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
            'department_id' => 'Mã',
            'code' => 'Mã code',
            'name' => 'Tên',
            'description' => 'Mô tả',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];

    }

}
