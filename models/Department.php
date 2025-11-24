<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "department".
 *
 * @property int $department_id
 * @property string|null $name
 * @property string|null $code
 * @property string|null $description
 *
 * @property Staff[] $staff
 */
class Department extends \yii\db\ActiveRecord
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
    public function rules()
    {
        return [
            [['name', 'code', 'description'], 'default', 'value' => null],
            [['description'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'department_id' => 'Department ID',
            'name' => 'Name',
            'code' => 'Code',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Staff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStaff()
    {
        return $this->hasMany(Staff::class, ['department_id' => 'department_id']);
    }

}
