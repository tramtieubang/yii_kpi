<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_kpi_evaluation".
 *
 * @property int $id ID đánh giá KPI
 * @property int $kpi_id ID KPI được đánh giá
 * @property int $employee_id ID nhân viên được đánh giá
 * @property float $score Điểm đánh giá
 * @property string|null $comment Nhận xét đánh giá
 * @property string $evaluated_at Thời gian đánh giá
 *
 * @property Employees $employee
 * @property KpiKpi $kpi
 */
class KpiKpiEvaluation extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_kpi_evaluation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment'], 'default', 'value' => null],
            [['kpi_id', 'employee_id', 'score'], 'required'],
            [['kpi_id', 'employee_id'], 'integer'],
            [['score'], 'number'],
            [['comment'], 'string'],
            [['evaluated_at'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employees::class, 'targetAttribute' => ['employee_id' => 'id']],
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
            'employee_id' => 'Employee ID',
            'score' => 'Score',
            'comment' => 'Comment',
            'evaluated_at' => 'Evaluated At',
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employees::class, ['id' => 'employee_id']);
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
