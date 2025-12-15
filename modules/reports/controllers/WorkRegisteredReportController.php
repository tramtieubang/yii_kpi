<?php
namespace app\modules\reports\controllers;

use Yii;
use yii\web\Controller;
use app\modules\reports\models\WorkRegisteredReportSearch;
use yii\filters\VerbFilter;

class WorkRegisteredReportController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors() {
    		return [
    			'ghost-access'=> [
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

    public function actionIndex()
    {
        $searchModel = new WorkRegisteredReportSearch();

        $params = Yii::$app->request->queryParams;
        $customSearch = $params['search'] ?? null;

        $dataProvider = $searchModel->search($params, $customSearch);

        return $this->render('@app/modules/reports/views/work-registered/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionFilter()
    {
        // Tạo search model
        $searchModel = new WorkRegisteredReportSearch();

        // Lấy toàn bộ params (POST ưu tiên, nếu rỗng thì dùng GET)
        $params = Yii::$app->request->post();
        if (empty($params)) {
            $params = Yii::$app->request->queryParams;
        }

        // Xử lý tìm kiếm
        $dataProvider = $searchModel->search($params);

        return $this->render('@app/modules/reports/views/work-registered/index', [
            'searchModel'  => $searchModel,
            'dataProvider'=> $dataProvider,
        ]);
    }

    public function actionExportExcel()
    {
        $searchModel = new WorkRegisteredReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $models = $dataProvider->getModels();

        // tạm thời dump dữ liệu
        // bước sau gắn PhpSpreadsheet
        return $this->renderPartial('export-preview', [
            //'models' => $models,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

}