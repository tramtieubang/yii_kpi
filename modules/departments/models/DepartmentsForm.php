<?php

namespace app\modules\departments\models;

use app\models\Departments;
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
class DepartmentsForm extends Departments
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'departments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['code', 'name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
            'id' => 'Mã',
            'code' => 'Mã code',
            'name' => 'Tên',
            'description' => 'Mô tả',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];

    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::class, ['department_id' => 'id']);
    }

}
