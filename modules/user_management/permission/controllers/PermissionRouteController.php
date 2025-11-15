<?php
namespace app\modules\user_management\permission\controllers;

use app\models\AuthItemChild;
use app\modules\user_management\permission\models\PermissionForm;
use app\modules\user_management\role\models\RoleForm;
use webvimark\modules\UserManagement\models\rbacDB\Route;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\web\Controller;
use Yii;
use yii\web\Response;

class PermissionRouteController extends Controller
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

    public function actionIndex($id)
    {
        //var_dump($id);

        $role_permissoin = RoleForm::find()
                        ->alias('i')
                        ->innerJoin(['c' => AuthItemChild::tableName()], 'c.child = i.name')
                        ->where(['c.parent' => $id, 'i.type' => 3])
                        ->groupBy(['c.child'])
                        ->orderBy(['c.child' => SORT_ASC])
                        ->select(['c.child'])
                        ->column();
        
        $routes = PermissionForm::find()
                        ->alias('i')
                        ->select(['i.name', 'i.description'])
                        ->where(['i.type' => 3])
                        ->andWhere("
                                i.name NOT REGEXP '^/(user-management|debug|migration|gridview|rbac|audit|log)/'
                            ")
                        /* ->andWhere("
                                i.name NOT REGEXP '^/(user-management|debug|gii|site|migration|gridview|rbac|audit|log)/'
                            ") */
                        ->orderBy(['i.description' => SORT_ASC])
                        ->asArray()
                        ->all();                
        /* 
                        $routes = PermissionForm::find()
                        ->alias('i')
                        ->select(['i.name', 'i.description', 'g.name AS group'])
                        ->innerJoin(['g' => AuthItemGroup::tableName()], 'g.code = i.group_code')
                        ->where(['i.type' => 3])
                        ->groupBy(['i.name'])
                        ->orderBy(['i.group_code' => SORT_ASC, 'i.description' => SORT_ASC])
                        ->asArray()
                        ->all();  */  
        
        return $this->asJson([
            'title' => 'Chức năng của quyền (route)',
            'forceReload' => '#crud-datatable-pjax', // Reload lại GridView sau khi lưu
            'success' => true,
            'content' => $this->renderAjax('/permission-route/permission-route', [
                'role_permissoin' => $role_permissoin, // Truyền model sang view
                'routes' => $routes,
                'roleName' => $id,
            ]),
            'footer' =>
                Html::button('Đóng lại', [
                    'class' => 'btn btn-default pull-left',
                    'data-bs-dismiss' => 'modal'
                ]) .
                Html::button('Lưu lại', [
                    'class' => 'btn btn-primary',
                    //'type' => 'button', // để xử lý bằng JS, tránh reload trang
                    'type' => 'submit', 
                    'id' => 'btn-save-permission-route'
                ]),
        ]); 
    }

    public function actionSaveRoutes($id)
    {
        $request = Yii::$app->request;

        // Lấy danh sách quyền được chọn từ form
        $routes = $request->post('routes', []); // mảng tên quyền hoặc []

        // Bắt đầu transaction để đảm bảo dữ liệu an toàn
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Xóa hết quyền cũ của role
            AuthItemChild::deleteAll(['parent' => $id]);

            // Chèn quyền mới
            foreach ($routes as $child) {
                $model = new AuthItemChild();
                $model->parent = $id;   // role
                $model->child = $child; // quyền
                $model->save();    // bỏ validate nếu dữ liệu đảm bảo
            }

            $transaction->commit();

            // Nếu là request Ajax (ví dụ modal), trả về JSON
            if ($request->isAjax) {
               /*  return $this->asJson([
                    'success' => true,
                    'message' => 'Lưu phân quyền thành công.'
                ]); */

                Yii::$app->response->format = Response::FORMAT_JSON;

               $role_permissoin = RoleForm::find()
                        ->alias('i')
                        ->innerJoin(['c' => AuthItemChild::tableName()], 'c.child = i.name')
                        ->where(['c.parent' => $id, 'i.type' => 3])
                        ->groupBy(['c.child'])
                        ->orderBy(['c.child' => SORT_ASC])
                        ->select(['c.child'])
                        ->column();
        
                $routes = PermissionForm::find()
                        ->alias('i')
                        ->select(['i.name', 'i.description'])
                        ->where(['i.type' => 3])
                        ->andWhere("
                                i.name NOT REGEXP '^/(user-management|debug|migration|gridview|rbac|audit|log)/'
                            ")
                        ->orderBy(['i.description' => SORT_ASC])
                        ->asArray()
                        ->all();   


                return $this->asJson([
                    'title' => 'Chức năng của quyền (route)',
                    'forceReload' => '#crud-datatable-pjax', // Reload lại GridView sau khi lưu
                    'success' => true,
                    'content' => $this->renderAjax('/permission-route/permission-route', [
                        'role_permissoin' => $role_permissoin, // Truyền model sang view
                        'routes' => $routes,
                        'roleName' => $id,
                    ]),
                    'tcontent'=>'Cập nhật quyền thành công!',
                    'footer' =>
                        Html::button('Đóng lại', [
                            'class' => 'btn btn-default pull-left',
                            'data-bs-dismiss' => 'modal'
                        ]) .
                        Html::button('Lưu lại', [
                            'class' => 'btn btn-primary',
                            //'type' => 'button', // để xử lý bằng JS, tránh reload trang
                            'type' => 'submit', 
                            'id' => 'btn-save-permission-route'
                        ]),
                ]); 
            }

            // Nếu là form bình thường
            //Yii::$app->session->setFlash('success', 'Lưu phân quyền thành công.');
            //return $this->redirect(['role/index']); // hoặc quay lại trang role-permission
            
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);

            if ($request->isAjax) {
                return $this->asJson([
                    'success' => false,
                    'message' => 'Lỗi khi lưu phân quyền.'.$e->getMessage()
                ]);
            }

            Yii::$app->session->setFlash('error', 'Lỗi khi lưu phân quyền.'.$e->getMessage());           
            //return $this->redirect(['role/index']);
        }
    }

    public function actionRefreshRoutes($id, $deleteUnused = null)
	{
        $request = Yii::$app->request;
        
		//return $this->redirect(['view', 'id'=>$id]);

         if ($request->isAjax) {
            
		    Route::refreshRoutes($deleteUnused !== null);

            Yii::$app->response->format = Response::FORMAT_JSON;

            $role_permissoin = RoleForm::find()
                    ->alias('i')
                    ->innerJoin(['c' => AuthItemChild::tableName()], 'c.child = i.name')
                    ->where(['c.parent' => $id, 'i.type' => 3])
                    ->groupBy(['c.child'])
                    ->orderBy(['c.child' => SORT_ASC])
                    ->select(['c.child'])
                    ->column();
    
            $routes = PermissionForm::find()
                    ->alias('i')
                    ->select(['i.name', 'i.description'])
                    ->where(['i.type' => 3])
                    ->andWhere("
                                i.name NOT REGEXP '^/(user-management|debug|migration|gridview|rbac|audit|log)/'
                            ")
                    ->orderBy(['i.description' => SORT_ASC])
                    ->asArray()
                    ->all();   

            return $this->asJson([
                'title' => 'Chức năng của quyền (route)',
                'forceReload' => '#crud-datatable-pjax', // Reload lại GridView sau khi lưu
                'success' => true,
                'content' => $this->renderAjax('/permission-route/permission-route', [
                    'role_permissoin' => $role_permissoin, // Truyền model sang view
                    'routes' => $routes,
                    'roleName' => $id,
                ]),
                'tcontent'=>'Cập nhật lại tất cả route mới thành công!',
                'footer' =>
                    Html::button('Đóng lại', [
                        'class' => 'btn btn-default pull-left',
                        'data-bs-dismiss' => 'modal'
                    ]) .
                    Html::button('Lưu lại', [
                        'class' => 'btn btn-primary',
                        //'type' => 'button', // để xử lý bằng JS, tránh reload trang
                        'type' => 'submit', 
                        'id' => 'btn-save-permission-route'
                    ]),
            ]); 
        }
	}


}