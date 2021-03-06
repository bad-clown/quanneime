<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace hipstercreative\user;

use yii\base\Component;

/**
 * ModelManager is used in order to create models and find users.
 *
 * @method models\User                createUser
 * @method models\Profile             createProfile
 * @method models\Account             createAccount
 * @method models\ResendForm          createResendForm
 * @method models\LoginForm           createLoginForm
 * @method models\RecoveryForm        createPasswordRecoveryForm
 * @method models\RecoveryRequestForm createPasswordRecoveryRequestForm
 * @method \yii\mongodb\ActiveQuery   createUserQuery
 * @method \yii\mongodb\ActiveQuery   createProfileQuery
 * @method \yii\mongodb\ActiveQuery   createAccountQuery
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class ModelManager extends Component
{
    /**
     * @var string
     */
    public $userClass = 'hipstercreative\user\models\User';

    /**
     * @var string
     */
    public $profileClass = 'hipstercreative\user\models\Profile';

    /**
     * @var string
     */
    public $accountClass = 'hipstercreative\user\models\Account';

    /**
     * @var string
     */
    public $resendFormClass = 'hipstercreative\user\models\ResendForm';

    /**
     * @var string
     */
    public $loginFormClass = 'hipstercreative\user\models\LoginForm';

    /**
     * @var string
     */
    public $recoveryFormClass = 'hipstercreative\user\models\RecoveryForm';

    /**
     * @var string
     */
    public $recoveryRequestFormClass = 'hipstercreative\user\models\RecoveryRequestForm';

    /**
     * Finds a user by id.
     *
     * @param integer $id
     *
     * @return null|models\User
     */
    public function findUserById($id)
    {
        return $this->findUser(['_id' => $id])->one();
    }

    /**
     * Finds a user by username.
     *
     * @param string $username
     *
     * @return null|models\User
     */
    public function findUserByUsername($username)
    {
        return $this->findUser(['username' => $username])->one();
    }

    /**
     * Finds a user by email.
     *
     * @param string $email
     *
     * @return null|models\User
     */
    public function findUserByEmail($email)
    {
        return $this->findUser(['email' => $email])->one();
    }

    public function findUserByPhoneNo($phoneno)
    {
        return $this->findUser(['phoneno' => $phoneno])->one();
    }

    /**
     * Finds a user either by email, or username.
     *
     * @param string $value
     *
     * @return null|models\User
     */
    public function findUserByUsernameOrEmail($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($value);
        }

        return $this->findUserByUsername($value);
    }

    public function findUserByUsernameOrEmailOrPhoneNo($value)
    {
        if (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return $this->findUserByEmail($value);
        }

        $u = $this->findUserByUsername($value);
        $phonenoUser = $this->findUserByPhoneNo($value);

        return $u==null? $phonenoUser :$u;
    }

    /**
     * Finds a user by id and confirmation token
     *
     * @param integer $id
     * @param string  $token
     *
     * @return null|models\User
     */
    public function findUserByIdAndConfirmationToken($id, $token)
    {
        return $this->findUser(['_id' => $id, 'confirmation_token' => $token])->one();
    }

    /**
     * Finds a user by id and recovery token
     *
     * @param integer $id
     * @param string  $token
     *
     * @return null|models\User
     */
    public function findUserByIdAndRecoveryToken($id, $token)
    {
        return $this->findUser(['_id' => $id, 'recovery_token' => $token])->one();
    }

    /**
     * Finds a user
     *
     * @param  $condition
     * @return \yii\mongodb\ActiveQuery
     */
    public function findUser($condition)
    {
        return $this->createUserQuery()->where($condition);
    }

    /**
     * Finds a profile by user id.
     *
     * @param integer $id
     *
     * @return null|models\Profile
     */
    public function findProfileById($id)
    {
        return $this->findProfile(['user_id' => $id])->one();
    }

    /**
     * Finds a profile
     *
     * @param  mixed $condition
     *
     * @return \yii\mongodb\ActiveQuery
     */
    public function findProfile($condition)
    {
        return $this->createProfileQuery()->where($condition);
    }

    /**
     * Finds an account by id.
     *
     * @param integer $id
     *
     * @return models\Account|null
     */
    public function findAccountById($id)
    {
        return $this->createAccountQuery()->where(['_id' => $id])->one();
    }

    /**
     * Finds an account by client id and provider name.
     *
     * @param string $provider
     * @param string $clientId
     *
     * @return models\Account|null
     */
    public function findAccount($provider, $clientId)
    {
        return $this->createAccountQuery()->where([
            'provider'  => $provider,
            'client_id' => $clientId
        ])->one();
    }

    /**
     * @param string $name
     * @param array $params
     * @return mixed|object
     */
    public function __call($name, $params)
    {
        $property = (false !== ($query = strpos($name, 'Query'))) ? mb_substr($name, 6, -5) : mb_substr($name, 6);
        $property = lcfirst($property) . 'Class';
        if ($query) {
            return forward_static_call([$this->$property, 'find']);
        }
        if (isset($this->$property)) {
            $config = [];
            if (isset($params[0]) && is_array($params[0])) {
                $config = $params[0];
            }
            $config['class'] = $this->$property;
            return \Yii::createObject($config);
        }

        return parent::__call($name, $params);
    }
}
