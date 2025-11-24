<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_formula".
 *
 * @property int $id ID công thức
 * @property int $kpi_id ID KPI liên quan
 * @property string $formula Công thức tính KPI
 * @property string|null $description Mô tả công thức
 *
 * @property KpiKpi $kpi
 */
class KpiFormula extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_formula';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'default', 'value' => null],
            [['kpi_id', 'formula'], 'required'],
            [['kpi_id'], 'integer'],
            [['formula'], 'string'],
            [['description'], 'string', 'max' => 255],
            [['kpi_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiKpi::class, 'targetAttribute' => ['kpi_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'kpi_id' => 'Kpi ID',
            'formula' => 'Formula',
            'description' => 'Description',
        ];
    }

    /**
     * Gets query for [[Kpi]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getKpi()
    {
        return $this->hasOne(KpiKpi::class, ['id' => 'kpi_id']);
    }

}
