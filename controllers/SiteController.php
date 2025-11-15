<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use webvimark\modules\UserManagement\models\forms\LoginForm;
use app\modules\user_management\user\components\CustomGhostAccessControl;

class SiteController extends Controller
{
    /**
     * Behaviors: GhostAccessControl + VerbFilter
     */
   /*  public function behaviors()
    {
        return [
            'ghost-access' => [
                'class' => CustomGhostAccessControl::class,
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['POST'],
                ],
            ],
        ];
    } */

    public function behaviors()
    {
        return [
                'ghost-access' => [
                'class' => CustomGhostAccessControl::class,
                'only' => ['logout', 'check-session'], // Chỉ áp dụng behavior này cho các action này
            ],            
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                    'logout' => ['POST'],
                ],
            ],

        ];
    }

    public function actionCheckSession()
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $loggedOut = true; // mặc định là logout
        if (!Yii::$app->user->isGuest) {
            $sessionId = Yii::$app->session->id;
            $session = \app\modules\user_management\session_manager\models\UserSessionsForm::find()
                ->where(['session_id' => $sessionId])
                ->one();

            // Nếu session tồn tại và chưa bị logout hay revoke
            if ($session && $session->logout_time === null && !$session->revoked_by_admin) {
                $loggedOut = false;
            }
        }

        return [
            'logged_out' => $loggedOut
        ];
    }    

    /**
     * Actions chuẩn
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Trước khi thực thi action
     */
    public function beforeAction($action)
    {
         if (!parent::beforeAction($action)) return false;

        if ($action->controller->id === 'site' && in_array($action->id, ['login', 'logout', 'index'])) {
            $this->layout = $action->id === 'login' ? 'loginLayout' : 'main';
            return parent::beforeAction($action);
        }

        if (Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        else {
     
            $sessionId = Yii::$app->session->id;
            $session = \app\modules\user_management\session_manager\models\UserSessionsForm::find()
                ->where(['session_id' => $sessionId])
                ->one();

            if (!$session || $session->logout_time !== null || $session->revoked_by_admin) {
                Yii::$app->user->logout();
                Yii::$app->session->destroy();
                Yii::$app->response->redirect(['/site/login'])->send();
                return false;
            }   
        } 

        $this->layout = 'main';
        return parent::beforeAction($action);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;

        if ($exception !== null) {
            // Nếu là 404 Not Found → redirect home/default
            if ($exception instanceof \yii\web\NotFoundHttpException) {
                if (Yii::$app->user->isGuest) {
                    $this->layout = 'loginLayout';
                    return $this->redirect(['/site/login']);
                } else {
                     $this->layout = 'main';
                    return $this->redirect(['/home/default']);
                }
            }

            // Các lỗi khác → render error bình thường
            return $this->render('error', ['exception' => $exception]);
        }
    }

    /**
     * Trang index → home/default
     */
   public function actionIndex()
    {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'loginLayout';
            return $this->redirect(['site/login']);
        }

        // Nếu đã login → redirect home/default
        $this->layout = 'main';
        return $this->redirect(['/home/default']);
    }

    /**
     * Login
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {

             // Thêm luu session dang nhap vao CSDL 
            $session = new \app\modules\user_management\session_manager\models\UserSessionsForm();
            $session->user_id = Yii::$app->user->id;
            $session->session_id = Yii::$app->session->id;
            $session->ip_address = Yii::$app->request->userIP;
            $session->user_agent = Yii::$app->request->userAgent;
            $session->device_name = \app\modules\user_management\session_manager\helpers\DeviceHelper::detect(Yii::$app->request->userAgent);
            $session->login_time = date('Y-m-d H:i:s');
            $session->last_activity = date('Y-m-d H:i:s');
            //$session->is_active = true;
            $session->save(false);

            // Login thành công → redirect về trang trước hoặc home
            return $this->goBack(['/']);
        }

        $this->layout = 'loginLayout';
        return $this->render('login', ['model' => $model]);
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }    


    /**
     * Contact page
     */
    public function actionContact()
    {
        $model = new \app\models\ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');
            return $this->refresh();
        }
        return $this->render('contact', ['model' => $model]);
    }

    /**
     * About page
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
