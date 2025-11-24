<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_registered_history".
 *
 * @property int $id
 * @property int $work_registered_id ID công việc gốc
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả
 * @property string $date_start
 * @property string|null $date_end
 * @property string $action_type create, update, delete
 * @property int|null $updated_by ID người cập nhật
 * @property string $created_at
 *
 * @property KpiWorkRegistered $workRegistered
 */
class KpiWorkRegisteredHistory extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'kpi_work_registered_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'date_end', 'updated_by'], 'default', 'value' => null],
            [['action_type'], 'default', 'value' => 'update'],
            [['work_registered_id', 'title', 'date_start'], 'required'],
            [['work_registered_id', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['date_start', 'date_end', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['action_type'], 'string', 'max' => 20],
            [['work_registered_id'], 'exist', 'skipOnError' => true, 'targetClass' => KpiWorkRegistered::class, 'targetAttribute' => ['work_registered_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'work_registered_id' => 'Work Registered ID',
            'title' => 'Title',
            'description' => 'Description',
            'date_start' => 'Date Start',
            'date_end' => 'Date End',
            'action_type' => 'Action Type',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[WorkRegistered]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorkRegistered()
    {
        return $this->hasOne(KpiWorkRegistered::class, ['id' => 'work_registered_id']);
    }

}
