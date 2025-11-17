<?php

namespace app\modules\kpi\models;

use app\models\KpiKpi;
use app\models\KpiKpiEvaluation;
use app\models\KpiWorkRegistered;
use Yii;

/**
 * This is the model class for table "kpi_kpi".
 *
 * @property int $id ID KPI
 * @property string $code Mã KPI
 * @property string $name Tên KPI
 * @property string|null $description Mô tả KPI
 * @property string $created_at Thời gian tạo
 * @property string $updated_at Thời gian cập nhật
 *
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 */
class KpiKpiForm extends KpiKpi
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
            [['code', 'name'], 'required'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
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
