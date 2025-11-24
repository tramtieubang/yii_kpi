<?php

namespace app\modules\staff\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\staff\models\StaffForm;

/**
 * StaffSearch represents the model behind the search form about `app\modules\staff\models\StaffForm`.
 */
class StaffSearch extends StaffForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['staff_id', 'department_id', 'position_id', 'business_field_id'], 'integer'],
            [['name', 'email', 'phone', 'hire_date', 'created_at', 'updated_at'], 'safe'],
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
        $query = StaffForm::find();

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
            ['like', 'phone', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'staff_id' => $this->staff_id,
            'department_id' => $this->department_id,
            'position_id' => $this->position_id,
            'business_field_id' => $this->business_field_id,
            'hire_date' => $this->hire_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone]);
		}
        return $dataProvider;
    }
}
