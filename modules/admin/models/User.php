<?php

namespace app\modules\admin\models;

use Yii;
use app\components\BaseMongoActiveRecord;
use yii\helpers\ArrayHelper;
use yii\db\Query;
use app\components\Cache;

class User extends BaseMongoActiveRecord implements \yii\web\IdentityInterface {
    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $ckey = 'u' . $id;
        $user = Cache::get($ckey);
        if ($user === false) {
            $user = self::findOne($id);
            if ($user != null) {
                Cache::set($ckey, $user);
            }
        }
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        $ckey = 't' . $token;
        $user = Cache::get($ckey);
        if($user === false) {
            $user = self::find()->where(['authToken' => $token])->one();
            if ($user != null) {
                Cache::set($ckey, $user);
            }
        }
        return $user;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username, $loginType) {
        return self::find()->where(['username' => $username])->one();
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return (String)$this->_id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->authkey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->authkey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($passwordToValidate) {
        return \Yii::$app->getSecurity()->validatePassword($passwordToValidate, $this->pwd) == 1 && ($this->status == '1');
    }

    /**
     * @inheritdoc
     */
    public static function conllectionName() {
        return 'users';
    }

    public static function primaryKey() {
        return ['_id'];
    }

    public function attributes() {
        return   ["_id", "phone", "username", "pwd", "status", "authkey", "authtoken"];
    }

    /**
    *  去除密码
    */
    public function afterFind() {
        if (!empty($this->pwd)) {
            $this->pwd="";
        }
        return parent::afterFind();
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if (!empty($this->pwd)) { //保存前将md5值再hash一次保存到数据库中
                $this->pwd =  Yii::$app->getSecurity()->generatePasswordHash($this->pwd);
            } else { //密码不变，恢复成原来的
                $this->pwd = $this->getOldAttribute("pwd");
            }
            if (empty($this->authkey)) {
                $this->authkey =Yii::$app->getSecurity()->generateRandomString();
            }
            if (empty($this->authtoken)) {
                $this->authtoken =Yii::$app->getSecurity()->generateRandomString();
            }

            $model = $this;

            Cache::delete("u".$this["_id"]); // delete cache
            Cache::delete("t".$this["authtoken"]);
            return true;
        } else {
            return false;
        }
    }


    public function beforeDelete() {
        if (parent::beforeDelete()) {
            Cache::delete("u".$this["_id"]);
            Cache::delete("t".$this["authtoken"]);
            return true;
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['status'], 'integer'],
            [['authkey', 'authtoken'], 'string', 'max' => 36],
            [['username'], 'string', 'max' => 20],
            [['pwd'], 'string', 'max' => 100],
            [['authkey'], 'unique'],
            [['authtoken'], 'unique'],
            [['phone'], 'unique'],
            [['username'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => \Yii::t('app','UserName'),
            'pwd' => \Yii::t('app','Password'),
            'phone' => \Yii::t('app','Phone'),
            'status' =>\Yii::t('app','Status'),
            'authkey' => 'authkey',
            'authtoken' => 'authtoken',
        ];
    }
}

