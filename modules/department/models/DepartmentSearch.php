<?php

namespace app\modules\department\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\department\models\DepartmentForm;

/**
 * DepartmentSearch represents the model behind the search form about `app\modules\department\models\DepartmentForm`.
 */
class DepartmentSearch extends DepartmentForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department_id'], 'integer'],
            [['name', 'code', 'description'], 'safe'],
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
        $query = DepartmentForm::find();

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
			$query->andFilterWhere ( [ 'OR' ,['like', 'name', $cusomSearch],
            ['like', 'code', $cusomSearch],
            ['like', 'description', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'department_id' => $this->department_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'description', $this->description]);
		}
        return $dataProvider;
    }
}
