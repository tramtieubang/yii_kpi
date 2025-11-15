<?php
namespace app\modules\user_management\permission_group\controllers;

use app\models\AuthItem;
use app\models\AuthItemChild;
use app\models\AuthItemGroup;
use app\modules\user_management\permission\models\PermissionForm;
use app\modules\user_management\permission_group\models\PermissionGroupForm;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class GroupAddPermissionController extends Controller
{
     public function behaviors() {
    		return [
    			'ghost-access'=> [
    			'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        		],
    			'verbs' => [
    				'class' => VerbFilter::className(),
    				'actions' => [
    					'delete' => ['POST'],
    				],
    			],
		];
	}

    public function actionIndex($id,$name)
    {
        $group_permission = PermissionGroupForm::find()
            ->alias('i')
            ->innerJoin(['c' => AuthItem::tableName()], 'c.group_code = i.code')
            ->where(['i.code' => $id, 'c.type' => 2]) // ✅ sửa lại đúng bảng và cột
            ->orderBy(['c.name' => SORT_ASC])
            ->select(['c.name'])
            ->column();

        $permissions = PermissionForm::find()
            ->alias('i')
            ->select(['i.name', 'i.description'])
            ->where(['i.type' => 2])
            ->orderBy(['i.description' => SORT_ASC])
            ->asArray()
            ->all();                
        
        return $this->asJson([
            'title' => 'Nhóm quyền sử dụng',
            'forceReload' => '#crud-datatable-pjax', // Reload lại GridView sau khi lưu
            'success' => true,
            'content' => $this->renderAjax('/group-add-permission/group-add-permission', [
                'group_permission' => $group_permission, // Truyền model sang view
                'permissions' => $permissions,
                'id' => $id,
                'name' => $name,
            ]),
            'footer' =>
                Html::button('Đóng lại', [
                    'class' => 'btn btn-default pull-left',
                    'data-bs-dismiss' => 'modal'
                ]) .
                Html::button('Lưu lại', [
                    'class' => 'btn btn-primary',
                    'type' => 'button', // để xử lý bằng JS, tránh reload trang
                    //'type' => 'submit', 
                    'id' => 'btn-save-group-add-permission'
                ]),
        ]); 
    }

    public function actionSaveGroupAddPermissions($id)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        try {
            $permissions = Yii::$app->request->post('permissions', []);

            // Kiểm tra nhóm có tồn tại không
            $group = PermissionGroupForm::findOne(['code' => $id]);
            if (!$group) {
                return [
                    'success' => false,
                    'message' => 'Không tìm thấy nhóm quyền!',
                ];
            }

            // 1. Xóa group_code cũ khỏi các quyền đang thuộc nhóm này
            Yii::$app->db->createCommand()
                ->update('auth_item', ['group_code' => null], ['group_code' => $id])
                ->execute();

            // 2. Cập nhật group_code cho các quyền mới được chọn
            if (!empty($permissions)) {
                Yii::$app->db->createCommand()
                    ->update('auth_item', ['group_code' => $id], ['name' => $permissions])
                    ->execute();
            }

            return [
                'success' => true,
                'message' => 'Cập nhật quyền cho nhóm "' . $group->name . '" thành công!',
            ];
        } catch (\Throwable $e) {
            Yii::error($e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'message' => 'Đã xảy ra lỗi khi lưu: ' . $e->getMessage(),
            ];
        }
    }


}