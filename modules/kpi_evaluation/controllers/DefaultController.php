<?php

namespace app\modules\kpi_evaluation\controllers;

use yii\web\Controller;

/**
 * Default controller for the `kpi-evaluation` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
