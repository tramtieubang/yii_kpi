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
            [['id', 'work_registered_id', 'staff_id', 'status_id'], 'integer'],
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($cusomSearch != NULL){
			$query->andFilterWhere ( [ 
                'OR',
                ['like', 'title', $cusomSearch],
                ['like', 'color', $cusomSearch], 
                ['like', 'description', $cusomSearch]
            ]);
 
		} else {
        	$query->andFilterWhere([
            'id' => $this->id,
            'work_registered_id' => $this->work_registered_id,
            'staff_id' => $this->staff_id,
            'status_id' => $this->status_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'assigned_at' => $this->assigned_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
             ->andFilterWhere(['like', 'description', $this->description])
             ->andFilterWhere(['like', 'color', $this->color]);
		}
        return $dataProvider;
    }
}
