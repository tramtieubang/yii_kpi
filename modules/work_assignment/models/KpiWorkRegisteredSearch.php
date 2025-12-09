<?php

namespace app\modules\work_assignment\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\work_registered\models\KpiWorkRegisteredForm;

/**
 * KpiWorkRegisteredSearch represents the model behind the search form about `app\modules\work_registered\models\KpiWorkRegisteredForm`.
 */
class KpiWorkRegisteredSearch extends KpiWorkRegisteredForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'staff_id', 'kpi_id', 'status_id'], 'integer'],
            [['title', 'description', 'start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
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
    public function search($params, $customSearch = null)
    {
        $query = KpiWorkRegisteredForm::find();

         // === Luôn loại bỏ status_id = 2 ===
        $query->andWhere(['<>', 'status_id', 2]);

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

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if ($customSearch != null) {

            $query->andFilterWhere([
                'OR',
                ['like', 'title', $customSearch],
                ['like', 'description', $customSearch]
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'staff_id' => $this->staff_id,
                'kpi_id' => $this->kpi_id,
                'status_id' => $this->status_id,                
               
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
             // Lọc theo các trường bình thường
/*             ->andFilterWhere(['staff_id' => $this->staff_id])
            ->andFilterWhere(['status_id' => $this->status_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]); */

            $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'description', $this->description]);

            // ====== Lấy input ngày giờ ======
             // GỌI HÀM LỌC NGÀY
            $this->applyDateFilters($query); 
        }    

      return $dataProvider;    
    }

    public function searchChild($params, $customSearch = null)
    {
        $query = KpiWorkRegisteredForm::find();

         // === Luôn loại bỏ status_id = 2 ===
        $query->andWhere(['<>', 'status_id', 2]);

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

        $this->load($params);

        // Nếu không truyền gì hoặc params rỗng → trả về toàn bộ data

        if (!$this->validate()) {
            return $dataProvider;
        }
        
        if ($customSearch != null) {
            $query->andFilterWhere([
                'OR',
                ['like', 'title', $customSearch],
                ['like', 'description', $customSearch]
            ]);
        } else {
            $query->andFilterWhere([
                'id' => $this->id,
                'staff_id' => $this->staff_id,
                'kpi_id' => $this->kpi_id,
                'status_id' => $this->status_id,                
               
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);
             // Lọc theo các trường bình thường
/*             ->andFilterWhere(['staff_id' => $this->staff_id])
            ->andFilterWhere(['status_id' => $this->status_id])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]); */

            $query->andFilterWhere(['like', 'title', $this->title])
              ->andFilterWhere(['like', 'description', $this->description]);

            // ====== Lấy input ngày giờ ======
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


}// end class
