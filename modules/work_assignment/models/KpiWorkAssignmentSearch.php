<?php

namespace app\modules\work_assignment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\work_assignment\models\KpiWorkAssignmentForm;

/**
 * KpiWorkAssignmentSearch represents the model behind the search form about `app\modules\work_assignment\models\KpiWorkAssignmentForm`.
 */
class KpiWorkAssignmentSearch extends KpiWorkAssignmentForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'work_registered_id', 'staff_id', 'status_id', 'kpi_id'], 'integer'],
            [['start_date', 'end_date', 'title', 'description', 'color', 'assigned_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params, $cusomSearch=NULL)
    {
        $query = KpiWorkAssignmentForm::find();

        // Thêm group by nhân viên
        $query->groupBy('staff_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'start_date' => SORT_DESC,
                    'end_date'   => SORT_DESC,
                ],
            ],
        ]);

        $pageSize = Yii::$app->request->get('per-page', 20);
        $dataProvider->pagination = [
            'pageSize' => $pageSize,
            'pageSizeLimit' => [10, 100],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($cusomSearch != NULL){
			$query->andFilterWhere ( [ 'OR' ,['like', 'title', $cusomSearch],
            ['like', 'description', $cusomSearch],
            ['like', 'color', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
                'id' => $this->id,
                'work_registered_id' => $this->work_registered_id,
                'staff_id' => $this->staff_id,
                'status_id' => $this->status_id,
                'kpi_id' => $this->kpi_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'assigned_at' => $this->assigned_at,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'color', $this->color]);  

            //$query->andFilterWhere(['!=', 'status_id', 2]);    

              // GỌI HÀM LỌC NGÀY
            $this->applyDateFilters($query);     
		}
        return $dataProvider;
    }


    public function searchChild($params, $cusomSearch=NULL)
    {
        $query = KpiWorkAssignmentForm::find();

        // Thêm group by nhân viên
        //$query->groupBy('staff_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'start_date' => SORT_DESC,
                    'end_date'   => SORT_DESC,
                ],
            ],
        ]);

        $pageSize = Yii::$app->request->get('per-page', 20);
        $dataProvider->pagination = [
            'pageSize' => $pageSize,
            'pageSizeLimit' => [10, 100],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($cusomSearch != NULL){
			$query->andFilterWhere ( [ 'OR' ,['like', 'title', $cusomSearch],
            ['like', 'description', $cusomSearch],
            ['like', 'color', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
                'id' => $this->id,
                'work_registered_id' => $this->work_registered_id,
                'staff_id' => $this->staff_id,
                'status_id' => $this->status_id,
                'kpi_id' => $this->kpi_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'assigned_at' => $this->assigned_at,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description])
                ->andFilterWhere(['like', 'color', $this->color]);  

            //$query->andFilterWhere(['!=', 'status_id', 2]); 

              // GỌI HÀM LỌC NGÀY
            $this->applyDateFilters($query);     
		}
        return $dataProvider;
    }

    private function applyDateFilters($query)
    {
        $start_from = Yii::$app->request->post('start_from_date');
        $start_to   = Yii::$app->request->post('start_to_date');
        $end_from   = Yii::$app->request->post('end_from_date');
        $end_to     = Yii::$app->request->post('end_to_date');

        // Chỉ lấy YYYY-MM-DD
        $start_from = $start_from ? substr($start_from, 0, 10) : null;
        $start_to   = $start_to   ? substr($start_to, 0, 10) : null;
        $end_from   = $end_from   ? substr($end_from, 0, 10) : null;
        $end_to     = $end_to     ? substr($end_to, 0, 10) : null;

        // Lọc start_date
        if ($start_from && $start_to) {
            $query->andWhere(
                "DATE_FORMAT(start_date, '%Y-%m-%d') <= :to 
                AND DATE_FORMAT(start_date, '%Y-%m-%d') >= :from",
                [':from' => $start_from, ':to' => $start_to]
            );
        } elseif ($start_from) {
            $query->andWhere("DATE_FORMAT(start_date, '%Y-%m-%d') >= :from", [':from' => $start_from]);
        } elseif ($start_to) {
            $query->andWhere("DATE_FORMAT(start_date, '%Y-%m-%d') <= :to", [':to' => $start_to]);
        }

        // Lọc end_date
        if ($end_from && $end_to) {
            $query->andWhere(
                "DATE_FORMAT(end_date, '%Y-%m-%d') <= :to 
                AND DATE_FORMAT(end_date, '%Y-%m-%d') >= :from",
                [':from' => $end_from, ':to' => $end_to]
            );
        } elseif ($end_from) {
            $query->andWhere("DATE_FORMAT(end_date, '%Y-%m-%d') >= :from", [':from' => $end_from]);
        } elseif ($end_to) {
            $query->andWhere("DATE_FORMAT(end_date, '%Y-%m-%d') <= :to", [':to' => $end_to]);
        }
    }

}
