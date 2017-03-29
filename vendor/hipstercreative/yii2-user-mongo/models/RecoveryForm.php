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

use hipstercreative\user\helpers\ModuleTrait;
use yii\base\InvalidParamException;
use yii\base\Model;
use app\components\SMSVerifier;

/**
 * Model for collecting data on password recovery.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class RecoveryForm extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $password;

    public $phoneno;
    public $type;

    public $verifyCode;

    /**
     * @var integer
     */
    public $_id;

    /**
     * @var string
     */
    public $token;

    /**
     * @var \hipstercreative\user\models\User
     */
    private $_user;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidParamException
     */
    public function init()
    {
        parent::init();
        /*if ($this->_id == null || $this->token == null) {
            throw new \RuntimeException('Id and token should be passed to config');
        }

        $this->_user = $this->module->manager->findUserByIdAndRecoveryToken($this->_id, $this->token);
        if (!$this->_user) {
            throw new InvalidParamException('Wrong password reset token');
        }
        if ($this->_user->isRecoveryPeriodExpired) {
            throw new InvalidParamException('Token has been expired');
        }*/
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => \Yii::t('user', 'Password'),
            'phoneno' => \Yii::t('user', 'Phoneno'),
            'type' => \Yii::t('user', 'Type'),
            'verifyCode' => \Yii::t('user', 'Verify Code'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'default' => ['password', 'type', 'phoneno', 'verifyCode']
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['phoneno', 'required', 'on' => ['default']],
            ['phoneno', 'match', 'pattern' => '/^\d+$/'],
            ['phoneno', 'string', 'min' => 11, 'max' => 11],
            ['phoneno', function ($attr) {
                if ($this->module->manager->findUserByPhoneAndType($this->$attr, $this->type) == null) {
                    $this->addError($attr, '手机号未注册');
                }
            }, 'on' => ['default']],
            ['phoneno', 'trim'],

            ['verifyCode', 'required', 'on' => ['default']],
            ['verifyCode', function ($attr) {
                if (SMSVerifier::verifyCode($this->phoneno, $this->$attr, false) == false) {
                    $this->addError($attr, \Yii::t('user', 'Verify code is not valid'));
                }
            }, 'on' => ['default']],
        ];
    }

    /**
     * Resets user's password.
     *
     * @return bool
     */
    public function resetPassword()
    {
        if ($this->validate()) {
            $user = $this->module->manager->findUserByPhoneAndType($this->phoneno, $this->type);
            $user->resetPassword($this->password);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'recovery-form';
    }
}
