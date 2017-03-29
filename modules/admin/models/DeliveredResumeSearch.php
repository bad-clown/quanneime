<?php

namespace app\modules\admin\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\admin\models\DeliveredResume;
use hipstercreative\user\models\User;

/**
 * DeliveredResumeSearch represents the model behind the search form about `app\modules\admin\models\DeliveredResume`.
 */
class DeliveredResumeSearch extends DeliveredResume
{
    public $deliver;
    public $jobname;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [["deliver", 'jobname'],"safe"],
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
        $query = DeliveredResume::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $users = User::find()->andFilterWhere(['like', 'username', $this->deliver])->all();
        $userIds = array();
        foreach ($users as $user) {
            $userIds[] = $user->_id;
        }
        $query->andFilterWhere(['in', 'delivererId', $userIds]);
        $jobs = Job::find()->where(array('or', ['like', 'company', $this->jobname], ['like', 'name', $this->jobname]))->all();
        $jobIds = array();
        foreach ($jobs as $job) {
            $jobIds[] = $job->_id;
        }
        $query->andFilterWhere(['in', 'jobId', $jobIds]);

        return $dataProvider;
    }

    public function attributeLabels()
    {
        return [
            'deliver'      => \Yii::t('user', '投递者'),
            'jobname'   => \Yii::t('user', '投递公司或职位'),
            'time'      => \Yii::t('user', '投递时间'),
            'status'      => \Yii::t('user', '投递状态'),
        ];
    }
}
