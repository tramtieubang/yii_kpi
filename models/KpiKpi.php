<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_kpi".
 *
 * @property int $id ID KPI
 * @property string $code Mã KPI
 * @property string $name Tên KPI
 * @property string|null $unit Đơn vị tính
 * @property float|null $target Mục tiêu KPI
 * @property float|null $weight Trọng số KPI
 * @property string|null $description Mô tả KPI
 * @property string|null $color Màu đại diện KPI
 * @property string|null $created_at Ngày tạo
 * @property string|null $updated_at Ngày cập nhật
 *
 * @property KpiFormula[] $kpiFormulas
 * @property KpiKpiEvaluation[] $kpiKpiEvaluations
 * @property KpiWorkRegistered[] $kpiWorkRegistereds
 * @property KpiWorkRelation[] $kpiWorkRelations
 * @property KpiWorkResult[] $kpiWorkResults
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
            [['unit', 'target', 'weight', 'description', 'color'], 'default', 'value' => null],
            [['code', 'name'], 'required'],
            [['target', 'weight'], 'number'],
            [['description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['code', 'unit'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 255],
            [['color'], 'string', 'max' => 20],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'unit' => 'Unit',
            'target' => 'Target',
            'weight' => 'Weight',
            'description' => 'Description',
            'color' => 'Color',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[KpiFormulas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiFormulas()
    {
        return $this->hasMany(KpiFormula::class, ['kpi_id' => 'id']);
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

    /**
     * Gets query for [[KpiWorkRelations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkRelations()
    {
        return $this->hasMany(KpiWorkRelation::class, ['kpi_id' => 'id']);
    }

    /**
     * Gets query for [[KpiWorkResults]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpiWorkResults()
    {
        return $this->hasMany(KpiWorkResult::class, ['kpi_id' => 'id']);
    }

}
