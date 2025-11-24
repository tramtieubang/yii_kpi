<?php

namespace app\modules\kpi\models;

use app\models\KpiFormula;
use app\models\KpiKpi;
use app\models\KpiKpiEvaluation;
use app\models\KpiWorkRegistered;
use app\models\KpiWorkRelation;
use app\models\KpiWorkResult;
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
     public function attributeLabels()
    {
       return [
            'id' => 'ID',
            'code' => 'Mã',
            'name' => 'Tên',
            'unit' => 'Đơn vị',
            'target' => 'Mục tiêu',
            'weight' => 'Trọng số',
            'description' => 'Mô tả',
            'color' => 'Màu',
            'created_at' => 'Ngày tạo',
            'updated_at' => 'Ngày cập nhật',
        ];

    }

}
