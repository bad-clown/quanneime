<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\InviteCode;

/**
 * InviteCodeSearch represents the model behind the search form about `app\modules\admin\models\InviteCode`.
 */
class InviteCodeSearch extends InviteCode
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["code","userId", 'status'],"safe"],
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
        $query = InviteCode::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        if(!empty($this->code)){
            $query->andWhere(["like","code",$this->code]);
        }
        if(!empty($this->status)){
            $query->andWhere(["status"=>intval($this->status)]);
        }

        return $dataProvider;
    }
}
