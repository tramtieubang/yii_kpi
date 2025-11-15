<?php

namespace app\modules\user_management\user\components;

use Yii;
use webvimark\modules\UserManagement\components\GhostAccessControl as BaseGhost;

class CustomGhostAccessControl extends BaseGhost
{
    /**
     * Trước khi thực thi action
    */    
    public function beforeAction($action)
    {
        // Cho phép login, logout và site/index không bị chặn
        if ($action->controller->id === 'site' && in_array($action->id, ['login', 'logout', 'index'])) {
            return true;
        }

        return parent::beforeAction($action);
    }

    /**
     * Kiểm tra quyền action
    */
    protected function isActionAllowed($action)
    {
        $route = '/' . $action->controller->id . '/' . $action->id;

        // Lấy route từ session
        $userRoutes = Yii::$app->session->get('__userRoutes', []);

        // Cho phép route mặc định khi đã login
        if (Yii::$app->user->isGuest === false && empty($userRoutes)) {
            $userRoutes[] = '/home/default';
        }

        return in_array($route, $userRoutes) || in_array('/' . $action->controller->id . '/*', $userRoutes);
    }

}
