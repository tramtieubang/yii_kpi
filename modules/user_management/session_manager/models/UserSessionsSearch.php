<?php

namespace app\modules\user_management\session_manager\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\user_management\session_manager\models\UserSessionsForm;

/**
 * UserSessionsSearch represents the model behind the search form about `app\modules\user_management\session_manager\models\UserSessionsForm`.
 */
class UserSessionsSearch extends UserSessionsForm
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['session_id', 'ip_address', 'user_agent', 'device_name', 'login_time', 'last_activity', 'logout_time', 'revoked_by_admin'], 'safe'],
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
        $query = UserSessionsForm::find();

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
			$query->andFilterWhere ( [ 'OR' ,['like', 'session_id', $cusomSearch],
            ['like', 'ip_address', $cusomSearch],
            ['like', 'user_agent', $cusomSearch],
            ['like', 'device_name', $cusomSearch],
            ['like', 'revoked_by_admin', $cusomSearch]] );
 
		} else {
        	$query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'login_time' => $this->login_time,
            'last_activity' => $this->last_activity,
            'logout_time' => $this->logout_time,
        ]);

        $query->andFilterWhere(['like', 'session_id', $this->session_id])
            ->andFilterWhere(['like', 'ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'user_agent', $this->user_agent])
            ->andFilterWhere(['like', 'device_name', $this->device_name])
            ->andFilterWhere(['like', 'revoked_by_admin', $this->revoked_by_admin]);
		}
        return $dataProvider;
    }
}
