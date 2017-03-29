<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Goods;

/**
 * GoodsSearch represents the model behind the search form about `app\modules\admin\models\Goods`.
 */
class GoodsSearch extends Goods
{
    public $minPrice;
    public $maxPrice;
    public $minCashBack;
    public $maxCashBack;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["name","category"],"safe"],
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
        $query = Goods::find()->where(["delete"=>false,"mainGoodsId"=>null]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['>=', 'price', $this->minPrice])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['<=', 'price', $this->maxPrice])
            ->andFilterWhere(['>=', 'cashBack', $this->minCashBack])
            ->andFilterWhere(['<=', 'cashBack', $this->maxCashBack]);

        return $dataProvider;
    }

    public function getCategoryList($dataProvider) {
        $models = $dataProvider->getModels();
        $cateIds = array();
        foreach ($models as $model) {
            $cateIds[] = $model['category'];
        }
        $cateList = Category::find()->where(['id'=>$cateIds])->all();
        $ret = array();
        foreach ($cateList as $cate) {
            $ret[(string)$cate->_id] = $cate->name;
        }
        return $ret;
    }
}
