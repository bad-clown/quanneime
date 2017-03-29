<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Category;
use yii\helpers\ArrayHelper;

/**
 * CategorySearch represents the model behind the search form about `app\modules\admin\models\Category`.
 */
class CategorySearch extends Category
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'parent'], 'safe'],
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
        $query = Category::find()->where(['delete' => false]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination'=>[
                "defaultPageSize"=> isset($params["pagesize"])? intval($params["pagesize"]):10
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }

    public function searchMap($params){
        return ArrayHelper::map($this->search($params)->getModels(),function($row){
            return (string)$row["_id"];
        },"name");
    }
}
