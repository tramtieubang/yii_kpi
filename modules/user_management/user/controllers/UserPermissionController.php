<?php
namespace app\modules\user_management\user\controllers;

use app\models\AuthAssignment;
use app\models\AuthItemChild;
use app\models\AuthItemGroup;
use app\modules\user_management\permission\models\PermissionForm;
use app\modules\user_management\role\models\RoleForm;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use Yii;

class UserPermissionController extends Controller
{
    public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * 🟦 Hiển thị giao diện phân quyền người dùng
     */
    public function actionIndex($id, $user)
    {
        // 1️⃣ Vai trò hiện tại của user
        $user_role = PermissionForm::find()
            ->alias('p')
            ->innerJoin(['c' => AuthAssignment::tableName()], 'c.item_name = p.name')
            ->where([
                'c.user_id' => $id,
                'p.type' => 1,
            ])
            ->orderBy(['p.description' => SORT_ASC])
            ->select(['p.name', 'p.description'])
            ->asArray()
            ->all();

        $user_role_names = array_column($user_role, 'name');

        // 2️⃣ Tất cả role trong hệ thống
        $roles = PermissionForm::find()
            ->alias('i')
            ->select(['i.name', 'i.description'])
            ->where(['i.type' => 1])
            ->orderBy(['i.description' => SORT_ASC])
            ->asArray()
            ->all();

        // 3️⃣ Các quyền (permission) mà role của user có
        $role_permissions = RoleForm::find()
            ->alias('i')
            ->innerJoin(['c' => AuthItemChild::tableName()], 'c.child = i.name')
            ->where(['in', 'c.parent', $user_role_names])
            ->andWhere(['i.type' => 2])
            ->groupBy(['c.child'])
            ->orderBy(['c.child' => SORT_ASC])
            ->select(['c.child'])
            ->column();

        // 4️⃣ Danh sách toàn bộ permission
        $permissions = PermissionForm::find()
            ->alias('i')
            ->select(['i.name', 'i.description', 'g.name AS group_name'])
            ->innerJoin(['g' => AuthItemGroup::tableName()], 'g.code = i.group_code')
            ->where(['i.type' => 2])
            ->orderBy(['i.group_code' => SORT_ASC, 'i.description' => SORT_ASC])
            ->asArray()
            ->all();

        // 5️⃣ Các route mà quyền đó có
        $role_routes = RoleForm::find()
            ->alias('i')
            ->innerJoin(['c' => AuthItemChild::tableName()], 'c.child = i.name')
            ->where(['in', 'c.parent', $role_permissions])
            ->andWhere(['i.type' => 3])
            ->groupBy(['c.child'])
            ->orderBy(['c.child' => SORT_ASC])
            ->select(['c.child'])
            ->column();

        // 6️⃣ Tất cả route trong hệ thống
        $routes = PermissionForm::find()
            ->alias('i')
            ->select(['i.name', 'i.description'])
            ->where(['i.type' => 3])
            ->andWhere("
                    i.name NOT REGEXP '^/(user-management|debug|migration|gridview|rbac|audit|log)/'
                ")
            ->orderBy(['i.name' => SORT_ASC])
            ->asArray()
            ->all();

        // ✅ Trả về view AJAX
        return $this->asJson([
            'title' => 'Phân quyền người dùng',
            'success' => true,
            'content' => $this->renderAjax('/user-permission/user-permission', [
                'user_role' => $user_role,
                'roles' => $roles,
                'role_permissions' => $role_permissions,
                'permissions' => $permissions,
                'role_routes' => $role_routes,
                'routes' => $routes,
                'id' => $id,
                'user' => $user,
            ]),
            'footer' => Html::button('Đóng lại', [
                'class' => 'btn btn-default pull-left',
                'data-bs-dismiss' => 'modal',
            ]),
        ]);
    }

    /**
     * 🟩 Lấy danh sách permission & route của các role
     */
    public function actionAjaxGetByRole()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $roles = Yii::$app->request->get('roles', []);
        if (empty($roles)) {
            return [
                'success' => true,
                'permissions' => [],
                'routes' => [],
            ];
        }

        // Lấy permissions (type=2) của các role
        $permissions = (new \yii\db\Query())
            ->select(['c.child'])
            ->from(['c' => 'auth_item_child'])
            ->innerJoin(['i' => 'auth_item'], 'c.child = i.name')
            ->where(['in', 'c.parent', $roles])
            ->andWhere(['i.type' => 2])
            ->groupBy(['c.child'])
            ->orderBy(['c.child' => SORT_ASC])
            ->column();

        // Nếu bạn quản lý route riêng (type=3), lấy tiếp
        $routes = [];
        if (!empty($permissions)) {
            $routes = (new \yii\db\Query())
                ->select(['c.child'])
                ->from(['c' => 'auth_item_child'])
                ->innerJoin(['i' => 'auth_item'], 'c.child = i.name')
                ->where(['in', 'c.parent', $permissions])
                ->andWhere(['i.type' => 3]) // routes nếu bạn lưu type=3
                ->andWhere("
                        i.name NOT REGEXP '^/(user-management|debug|migration|gridview|rbac|audit|log)/'
                    ")
                ->groupBy(['c.child'])
                ->orderBy(['c.child' => SORT_ASC])
                ->column();
        }

        return [
            'success' => true,
            'permissions' => array_unique($permissions),
            'routes' => array_unique($routes),
        ];
    }

    /**
     * 🟥 Lưu lại vai trò người dùng
     */
    public function actionSaveRoles()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user_id = Yii::$app->request->post('user_id');
        $roles = Yii::$app->request->post('roles', []);

        if (empty($user_id)) {
            return [
                'success' => false,
                'message' => 'Không xác định được người dùng cần cập nhật.',
            ];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Xóa vai trò cũ
            AuthAssignment::deleteAll(['user_id' => $user_id]);

            // Thêm vai trò mới
            foreach ($roles as $item_name) {
                $model = new AuthAssignment();
                $model->user_id = $user_id;
                $model->item_name = $item_name;
                $model->save(false);
            }

            $transaction->commit();
            return [
                'success' => true,
                'message' => 'Cập nhật vai trò thành công.',
            ];

        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            return [
                'success' => false,
                'message' => 'Lỗi khi lưu vai trò: ' . $e->getMessage(),
            ];
        }
    }
}
