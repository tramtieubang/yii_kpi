<?php
namespace app\modules\user_management\session_manager\components;

use yii\web\Controller as BaseController;
use Yii;

class Controller extends BaseController
{
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }
        return parent::beforeAction($action);
    }
}
