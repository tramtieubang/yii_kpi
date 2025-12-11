<?php

namespace app\modules\home\controllers;

use app\custom\PermissionHelper;
use app\modules\user_management\user\models\User;
use webvimark\modules\UserManagement\components\GhostAccessControl;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `home` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */

    public function behaviors() {
			
    		return [
    			'ghost-access'=> [
    			'class' => 'webvimark\modules\UserManagement\components\GhostAccessControl',
        		],
    			'verbs' => [
    				'class' => \yii\filters\VerbFilter::class,
    				'actions' => [
    					'delete' => ['POST'],
    				],
    			],
		];
	}

	
    public function actionIndex()
    {
		//$canRegister = PermissionHelper::check('work-registered/register/index'); 		
        //dd($canRegister);
       //dd(Yii::$app->user->can('/work-registered/register/index'));
	   //dd(PermissionHelper::check('work-registered/register'));

        return $this->render('index');
    }
}
