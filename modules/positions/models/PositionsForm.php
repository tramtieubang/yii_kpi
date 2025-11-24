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
    public function attributeLabels()
    {
      return [
            'position_id' => 'ID Chức vụ',
            'name' => 'Tên Chức vụ',
            'description' => 'Mô tả',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];

    }

}
