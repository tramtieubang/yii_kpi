<?php

namespace app\modules\business_fields\models;

use app\models\BusinessFields;
use app\models\Employees;
use Yii;

/**
 * This is the model class for table "business_fields".
 *
 * @property int $id ID lĩnh vực kinh doanh
 * @property string $name Tên lĩnh vực kinh doanh
 * @property string|null $description Mô tả lĩnh vực
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property Employees[] $employees
 */
class BusinessFieldsForm extends BusinessFields
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'business_fields';
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
            'id' => 'ID lĩnh vực',
            'name' => 'Tên lĩnh vực kinh doanh',
            'description' => 'Mô tả lĩnh vực',
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
        return $this->hasMany(Employees::class, ['business_field_id' => 'id']);
    }

}
