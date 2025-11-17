<?php

namespace app\modules\employees\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\employees\models\EmployeesForm;

/**
 * EmployeesSearch represents the model behind the search form about `app\modules\employees\models\EmployeesForm`.
 */
class EmployeesSearch extends EmployeesForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'department_id'], 'integer'],
            [['name', 'email', 'phone', 'position', 'hire_date', 'created_at', 'updated_at'], 'safe'],
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
        $query = EmployeesForm::find();

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
            ['like', 'email', $cusomSearch],
            ['like', 'phone', $cusomSearch],
            ['like', 'position', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'department_id' => $this->department_id,
            'hire_date' => $this->hire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'position', $this->position]);
		}
        return $dataProvider;
    }
}
