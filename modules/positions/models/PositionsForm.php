<?php

namespace app\modules\positions\models;

use app\models\Employees;
use app\models\Positions;
use Yii;

/**
 * This is the model class for table "positions".
 *
 * @property int $id ID chức vụ
 * @property string $name Tên chức vụ
 * @property string|null $description Mô tả chức vụ
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property Employees[] $employees
 */
class PositionsForm extends Positions
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'positions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
       return [
            'id' => 'ID chức vụ',
            'name' => 'Tên chức vụ',
            'description' => 'Mô tả chức vụ',
            'created_at' => 'Thời gian tạo',
            'updated_at' => 'Thời gian cập nhật',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employees::class, ['position_id' => 'id']);
    }

}
