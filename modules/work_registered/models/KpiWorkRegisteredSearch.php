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
            [['id', 'employee_id', 'kpi_id', 'status'], 'integer'],
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
    public function search($params, $cusomSearch = null)
    {
        $query = KpiWorkRegisteredForm::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Nếu có custom search
        if ($cusomSearch != null) {
            $query->andFilterWhere([
                'OR',
                ['like', 'title', $cusomSearch],
                ['like', 'description', $cusomSearch]
            ]);
        } else {
           /*  // Chỉ lọc bằng >= và <=, convert ngày sang Y-m-d
            if (!empty($this->date_start)) {
                $dateStart = \DateTime::createFromFormat('d/m/Y', $this->date_start);
                if ($dateStart) {
                    $query->andFilterWhere(['>=', 'date_start', $dateStart->format('Y-m-d')]);
                }
            }

            if (!empty($this->date_end)) {
                $dateEnd = \DateTime::createFromFormat('d/m/Y', $this->date_end);
                if ($dateEnd) {
                    $query->andFilterWhere(['<=', 'date_end', $dateEnd->format('Y-m-d')]);
                }
            } */

            // Nếu muốn OR thay vì AND
            $orCondition = ['or'];

            if (!empty($this->date_start)) {
                $dateStart = \DateTime::createFromFormat('d/m/Y', $this->date_start);
                if ($dateStart) {
                    $orCondition[] = ['>=', 'date_start', $dateStart->format('Y-m-d')];
                }
            }

            if (!empty($this->date_end)) {
                $dateEnd = \DateTime::createFromFormat('d/m/Y', $this->date_end);
                if ($dateEnd) {
                    $orCondition[] = ['<=', 'date_end', $dateEnd->format('Y-m-d')];
                }
            }

            if (count($orCondition) > 1) {
                $query->andFilterWhere($orCondition);
            }    

            // Filter các trường khác bình thường
            $query->andFilterWhere([
                'id' => $this->id,
                'employee_id' => $this->employee_id,
                'kpi_id' => $this->kpi_id,
                'status' => $this->status,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ]);

            $query->andFilterWhere(['like', 'title', $this->title])
                ->andFilterWhere(['like', 'description', $this->description]);
        }

        return $dataProvider;
    }


}
