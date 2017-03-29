<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\Order;
use app\modules\admin\models\User;
use yii\helpers\ArrayHelper;

/**
 * OrderSearch represents the model behind the search form about `app\modules\admin\models\Order`.
 */
class OrderSearch extends Order
{
    public $timeStart;
    public $timeEnd;
    public $username;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['orderId','status', 'timeStart','timeEnd','username'], 'safe'],
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
        $query = Order::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        if($this->timeStart!=""){
            $query->andWhere(["time"=>['$gte'=>intval(\Yii::$app->formatter->format( $this->timeStart,'timestamp'))]]);
        }
        if($this->timeEnd!=""){
            $query->andWhere(["time"=>['$lte'=>intval(\Yii::$app->formatter->format( $this->timeEnd,'timestamp'))]]);
        }
        if($this->username!=""){
            $userIds = User::find()->select(["_id"])->where(['like','username',$this->username])->all();
            $userIds = ArrayHelper::getColumn($userIds,'_id');
            $query->andWhere(["userId"=>$userIds]);
        }
        $query->andFilterWhere(['like', '_id', $this->_id])
            ->andFilterWhere(['like', 'orderId', $this->orderId])
            ->andFilterWhere(['like', 'chargeId', $this->chargeId])
            ->andFilterWhere(['status' => $this->status])
            ->andFilterWhere(['userId' => $this->userId]);

        return $dataProvider;
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(),[
            'username' => '用户',
        ]);
    }
}
