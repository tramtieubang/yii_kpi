<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_relation".
 *
 * @property int $id ID quan hệ
 * @property int $kpi_id ID KPI liên quan
 * @property int $assignment_id ID phân công công việc
 * @property float $weight Trọng số KPI cho công việc
 * @property float|null $actual Kết quả thực tế
 * @property float|null $target Mục tiêu KPI
 *
 * @property KpiWorkAssignment $assignment
 * @property KpiKpi $kpi
 */
class KpiWorkRelation extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_relation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['target'], 'default', 'value' => 0.00],
            [['kpi_id', 'assignment_id', 'weight'], 'required'],
            [['kpi_id', 'assignment_id'], 'integer'],
            [['weight', 'actual', 'target'], 'number'],
            [['assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignment::class, 'targetAttribute' => ['assignment_id' => 'id']],
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
            'assignment_id' => 'Assignment ID',
            'weight' => 'Weight',
            'actual' => 'Actual',
            'target' => 'Target',
        ];
    }

    /**
     * Gets query for [[Assignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(KpiWorkAssignment::class, ['id' => 'assignment_id']);
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
