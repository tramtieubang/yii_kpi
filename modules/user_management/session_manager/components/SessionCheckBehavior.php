<?php
namespace app\components;

use Yii;
use yii\base\Behavior;
use yii\web\User;
use app\modules\session_manager\models\Session;
use app\modules\user_management\session_manager\models\UserSessionsForm;

class CheckSessionBehavior extends Behavior
{
    public function events()
    {
        return [
            User::EVENT_BEFORE_LOGIN => 'onLogin',
        ];
    }

    public function onLogin($event)
    {
        // Không làm gì khi login
    }

    public function checkActiveSession()
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $sessionId = Yii::$app->session->id;
        $session = UserSessionsForm::find()
            ->where(['session_id' => $sessionId])
            ->andWhere(['is_active' => true])
            ->one();

        // Nếu session không còn hợp lệ (bị admin revoke)
        if (!$session) {
            Yii::$app->user->logout(false);
            Yii::$app->session->destroy();
            Yii::$app->response->redirect(['/site/login'])->send();
            exit;
        } else {
            // cập nhật hoạt động
            $session->last_activity = date('Y-m-d H:i:s');
            $session->save(false);
        }
    }
}
