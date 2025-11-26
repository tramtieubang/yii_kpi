<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "kpi_work_registered_history".
 *
 * @property int $id ID lịch sử
 * @property int $work_registered_id ID công việc đăng ký
 * @property string $title Tiêu đề công việc
 * @property string|null $description Mô tả công việc
 * @property string $start_date Ngày bắt đầu công việc
 * @property string|null $end_date Ngày kết thúc công việc
 * @property string|null $action_type Loại hành động (create/update/delete)
 * @property int|null $updated_by ID người cập nhật
 * @property string|null $created_at Ngày tạo bản ghi lịch sử
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
            [['description', 'end_date', 'updated_by'], 'default', 'value' => null],
            [['action_type'], 'default', 'value' => 'update'],
            [['work_registered_id', 'title', 'start_date'], 'required'],
            [['work_registered_id', 'updated_by'], 'integer'],
            [['description'], 'string'],
            [['start_date', 'end_date', 'created_at'], 'safe'],
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
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
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
