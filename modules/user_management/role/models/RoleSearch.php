<?php

namespace app\modules\user_management\role\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user_management\role\models\RoleForm;

/**
 * RoleSearch represents the model behind the search form about `app\modules\role\models\RoleForm`.
 */
class RoleSearch extends RoleForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'rule_name', 'data', 'group_code'], 'safe'],
            [['type', 'created_at', 'updated_at'], 'integer'],
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
        $query = RoleForm::find();

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
            ['like', 'description', $cusomSearch],
            ['like', 'rule_name', $cusomSearch],
            ['like', 'data', $cusomSearch],
            ['like', 'group_code', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'type' => $this->type,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'rule_name', $this->rule_name])
            ->andFilterWhere(['like', 'data', $this->data])
            ->andFilterWhere(['like', 'group_code', $this->group_code]);
		}
        return $dataProvider;
    }
}
