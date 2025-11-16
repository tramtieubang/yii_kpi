<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_report".
 *
 * @property int $id ID báo cáo công việc
 * @property int $work_assignment_id ID phân công công việc
 * @property string|null $content Nội dung báo cáo
 * @property string $reported_at Thời gian báo cáo
 *
 * @property KpiWorkAssignment $workAssignment
 */
class KpiWorkReport extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'default', 'value' => null],
            [['work_assignment_id'], 'required'],
            [['work_assignment_id'], 'integer'],
            [['content'], 'string'],
            [['reported_at'], 'safe'],
            [['work_assignment_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkAssignment::class, 'targetAttribute' => ['work_assignment_id' => 'id']],
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
            'content' => 'Content',
            'reported_at' => 'Reported At',
        ];
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
