<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_visit_log".
 *
 * @property int $id
 * @property string $token
 * @property string $ip
 * @property string $language
 * @property string $user_agent
 * @property int|null $user_id
 * @property int $visit_time
 * @property string|null $browser
 * @property string|null $os
 *
 * @property User $user
 */
class UserVisitLog extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_visit_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'browser', 'os'], 'default', 'value' => null],
            [['token', 'ip', 'language', 'user_agent', 'visit_time'], 'required'],
            [['user_id', 'visit_time'], 'integer'],
            [['token', 'user_agent'], 'string', 'max' => 255],
            [['ip'], 'string', 'max' => 15],
            [['language'], 'string', 'max' => 2],
            [['browser'], 'string', 'max' => 30],
            [['os'], 'string', 'max' => 20],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'ip' => 'Ip',
            'language' => 'Language',
            'user_agent' => 'User Agent',
            'user_id' => 'User ID',
            'visit_time' => 'Visit Time',
            'browser' => 'Browser',
            'os' => 'Os',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

}
