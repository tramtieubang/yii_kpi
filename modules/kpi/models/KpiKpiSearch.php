<?php

namespace app\modules\kpi\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\kpi\models\KpiKpiForm;

/**
 * KpiKpiSearch represents the model behind the search form about `app\modules\kpi\models\KpiKpiForm`.
 */
class KpiKpiSearch extends KpiKpiForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['code', 'name', 'unit', 'description', 'color', 'created_at', 'updated_at'], 'safe'],
            [['target', 'weight'], 'number'],
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
        $query = KpiKpiForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		if($cusomSearch != NULL){
			$query->andFilterWhere ( [ 'OR' ,['like', 'code', $cusomSearch],
            ['like', 'name', $cusomSearch],
            ['like', 'unit', $cusomSearch],
            ['like', 'description', $cusomSearch],
            ['like', 'color', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'id' => $this->id,
            'target' => $this->target,
            'weight' => $this->weight,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'color', $this->color]);
		}
        return $dataProvider;
    }
}
