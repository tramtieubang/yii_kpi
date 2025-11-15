<?php
namespace app\modules\user_management;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\user_management\controllers';

    public function init()
    {
        parent::init();

        // Tự động load module con
        $this->modules = [
            'user' => [
                'class' => 'app\modules\user_management\user\Module',
            ],
            'role' => [
                'class' => 'app\modules\user_management\role\Module',
            ],
            'permission' => [
                'class' => 'app\modules\user_management\permission\Module',
            ],
            'permission_group' => [
                'class' => 'app\modules\user_management\permission_group\Module',
            ],
            'session_manager' => [ 
                'class' => 'app\modules\user_management\session_manager\Module', 
            ],
        ];
    }
}
