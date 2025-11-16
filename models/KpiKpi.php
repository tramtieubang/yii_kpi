<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_kpi".
 *
 * @property int $id ID KPI
 * @property string $name Tên KPI
 * @property string|null $description Mô tả KPI
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 */
class KpiKpi extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_kpi';
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
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[KpiKpiEvaluations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiKpiEvaluations()
    {
        return $this->hasMany(KpiKpiEvaluation::class, ['kpi_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkRegistereds]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRegistereds()
    {
        return $this->hasMany(KpiWorkRegistered::class, ['kpi_id' => 'id']);
    }

}
