<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_registered_status".
 *
 * @property int $id
 * @property string $name Tên trạng thái
 * @property string|null $description Mô tả trạng thái
 *
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 */
class KpiWorkRegisteredStatus extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_registered_status';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[KpiWorkRegistereds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRegistereds()
    {
        return $this->hasMany(KpiWorkRegistered::class, ['status_id' => 'id']);
    }

}
