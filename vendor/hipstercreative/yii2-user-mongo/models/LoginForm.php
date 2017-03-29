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
use yii\base\Model;
use hipstercreative\user\helpers\Password;
use app\modules\admin\models\Points;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\PointsLogic;

/**
 * LoginForm is the model behind the login form.
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class LoginForm extends Model
{
    use ModuleTrait;

    /**
     * @var string
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var bool Whether to remember the user.
     */
    public $rememberMe = false;

    /**
     * @var \hipstercreative\user\models\User
     */
    protected $user;

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'login'      => \Yii::t('user', 'Login'),
            'password'   => \Yii::t('user', 'Password'),
            'rememberMe' => \Yii::t('user', 'Remember me next time'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['login', 'password'], 'required'],
            ['login', 'trim'],
            ['password', function ($attribute) {
                if ($this->user === null || !Password::validate($this->password, $this->user->password_hash)) {
                    $this->addError($attribute, \Yii::t('user', 'Invalid login or password'));
                }
            }],
            ['login', function ($attribute) {
                if ($this->user !== null) {
                    $confirmationRequired = $this->module->confirmable && !$this->module->allowUnconfirmedLogin;
                    if ($confirmationRequired && $this->user->confirmed_at==null) {
                        $this->addError($attribute, \Yii::t('user', 'You need to confirm your email address'));
                    }
                    if ($this->user->getIsBlocked()) {
                        $this->addError($attribute, \Yii::t('user', 'Your account has been blocked'));
                    }
                }
            }],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        $succ = false;
        if ($this->validate()) {
            $this->user->setAttribute('logged_in_from', ip2long(\Yii::$app->getRequest()->getUserIP()));
            $this->user->setAttribute('logged_in_at', time());
            $this->user->save(false);
            $succ = \Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 0);
        }
        if ($succ) {
            //todo:登陆加积分，一天一次
            $points = Points::find()->where(['userId' => $this->user->_id, 'type' => DictionaryLogic::indexKeyValue('PointsAddType', 'Login')])
                ->orderBy('time DESC')->one();
            if ($points == null || date(DateFormat, $points->time) < date(DateFormat, NOW)) {   // 今天还没记录，加一次积分
                PointsLogic::addPoints($this->user->_id, 'Login');
            }
        }
        return $succ;
    }

    /**
     * @inheritdoc
     */
    public function formName()
    {
        return 'login-form';
    }

    /**
     * @inheritdoc
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            $this->user = $this->module->manager->findUserByUsernameOrEmailOrPhoneNo($this->login);
            return true;
        } else {
            return false;
        }
    }
}
