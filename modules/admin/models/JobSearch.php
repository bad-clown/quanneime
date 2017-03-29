<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Job;
use yii\helpers\ArrayHelper;

/**
 * JobSearch represents the model behind the search form about `app\modules\admin\models\Job`.
 */
class JobSearch extends Job
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company', 'status'], 'safe'],
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
    public function search($params)
    {
        $query = Job::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*'pagination'=>[
                "defaultPageSize"=> isset($params["pagesize"])? intval($params["pagesize"]):10
                ],*/
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'company', $this->company])
            ->andFilterWhere(['status' => $this->status]);

        return $dataProvider;
    }

    public function searchMap($params){
        return ArrayHelper::map($this->search($params)->getModels(),function($row){
            return (string)$row["_id"];
        },"name");
    }
}
