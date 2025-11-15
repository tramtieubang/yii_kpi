<?php

namespace app\modules\user_management\role\models;

use app\models\Role;
use app\models\User;
use Yii;

/**
 * This is the model class for table "auth_item".
 *
 * @property string $name
 * @property int $type
 * @property string|null $description
 * @property string|null $rule_name
 * @property string|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property string|null $group_code
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItemChild[] $authItemChildren
 * @property AuthItemChild[] $authItemChildren0
 * @property Role[] $children
 * @property AuthItemGroup $groupCode
 * @property Role[] $parents
 * @property AuthRule $ruleName
 * @property User[] $users
 */
class RoleForm extends Role
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auth_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'rule_name', 'data', 'created_at', 'updated_at', 'group_code'], 'default', 'value' => null],
            [['name', 'description'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['type'], 'default', 'value' => 1], // 👉 type mặc định = 1
            [['description', 'data'], 'string'],
            [['name', 'rule_name', 'group_code'], 'string', 'max' => 64],
            [['name'], 'unique'],           
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'type' => 'Type',
            'description' => 'Mô tả',
            'rule_name' => 'Tên (key)',
            'data' => 'Data',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'group_code' => 'Group Code',
        ];
    }
 
}
