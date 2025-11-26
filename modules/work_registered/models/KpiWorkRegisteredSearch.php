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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ])
        ->andFilterWhere(['like', 'title', $this->title])
        ->andFilterWhere(['like', 'description', $this->description]);
    }

    return $dataProvider;
}


}
