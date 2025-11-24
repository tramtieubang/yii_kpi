<?php

namespace app\modules\work_registered\models;

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
            [['title', 'description', 'date_start', 'date_end', 'created_at', 'updated_at'], 'safe'],
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
        $query = KpiWorkRegisteredForm::find();

        $dataProvider = new ActiveDataProvider([
        'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_start' => SORT_DESC,
                    'date_end'   => SORT_DESC,
                ],
            ],
        ]);
       /*  $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]); */

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($cusomSearch != NULL){
			$query->andFilterWhere ( [ 'OR' ,['like', 'title', $cusomSearch],
            ['like', 'description', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'id' => $this->id,
            'staff_id' => $this->staff_id,
            'kpi_id' => $this->kpi_id,
            'status_id' => $this->status_id,
            'date_start' => $this->date_start,
            'date_end' => $this->date_end,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'description', $this->description]);
		}
        return $dataProvider;
    }
}
