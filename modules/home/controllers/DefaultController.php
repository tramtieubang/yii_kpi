<?php

namespace app\modules\home\controllers;

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
		
        return $this->render('index');
    }
}
