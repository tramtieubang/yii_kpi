<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_result".
 *
 * @property int $id ID kết quả
 * @property int $work_assignment_id ID phân công công việc
 * @property int $kpi_id ID KPI liên quan
 * @property float|null $actual Kết quả thực tế
 * @property float|null $target Mục tiêu KPI
 * @property float|null $weight Trọng số KPI
 * @property float|null $score Điểm KPI
 * @property int|null $month Tháng
 * @property int|null $year Năm
 * @property string|null $created_at Ngày tạo kết quả
 *
 * @property KpiKpi $kpi
 * @property KpiWorkAssignment $workAssignment
 */
class KpiWorkResult extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['month', 'year'], 'default', 'value' => null],
            [['score'], 'default', 'value' => 0.00],
            [['work_assignment_id', 'kpi_id'], 'required'],
            [['work_assignment_id', 'kpi_id', 'month', 'year'], 'integer'],
            [['actual', 'target', 'weight', 'score'], 'number'],
            [['created_at'], 'safe'],
            [['work_assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignment::class, 'targetAttribute' => ['work_assignment_id' => 'id']],
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
            'work_assignment_id' => 'Work Assignment ID',
            'kpi_id' => 'Kpi ID',
            'actual' => 'Actual',
            'target' => 'Target',
            'weight' => 'Weight',
            'score' => 'Score',
            'month' => 'Month',
            'year' => 'Year',
            'created_at' => 'Created At',
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

    /**
     * Gets query for [[WorkAssignment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkAssignment()
    {
        return $this->hasOne(KpiWorkAssignment::class, ['id' => 'work_assignment_id']);
    }

}
