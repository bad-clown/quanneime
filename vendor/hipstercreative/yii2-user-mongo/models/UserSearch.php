<?php

/*
* This file is part of the Dektrium project.
*
* (c) Dektrium project <http://github.com/dektrium/>
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace hipstercreative\user\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * UserSearch represents the model behind the search form about User.
 */
class UserSearch extends Model
{
    /**
     * @var string
     */
    public $username;

    /**
     * @var string
     */
    public $email;

    /**
     * @var integer
     */
    public $created_at;

    /**
     * @var string
     */
    public $registered_from;

    public $type;

    public $phoneno;

    public $authStatus;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'phoneno', 'email', 'type', 'authStatus'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('user', 'Username'),
            'email' => \Yii::t('user', 'Email'),
            'type' => \Yii::t('user', 'Type'),
            'phoneno' => \Yii::t('user', 'Phone Number'),
            'authStatus' => \Yii::t('user', 'Auth Status'),
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->addCondition($query, 'username', true);
        $this->addCondition($query, 'phoneno', true);
        $this->addCondition($query, 'email', true);
        $this->addCondition($query, 'type');
        $this->addCondition($query, 'authStatus');

        return $dataProvider;
    }

    /**
     * @param $query
     * @param $attribute
     * @param bool $partialMatch
     */
    protected function addCondition($query, $attribute, $partialMatch = false)
    {
        $value = $this->$attribute;
        if (trim($value) === '') {
            return;
        }
        if ($attribute == 'registered_from') {
            $value = ip2long($value);
        }
        if ($partialMatch) {
            $query->andWhere(['like', $attribute, $value]);
        } else {
            $query->andWhere([$attribute => $value]);
        }
    }
}
