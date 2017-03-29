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
use hipstercreative\user\helpers\Password;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\mongodb\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;
use app\modules\admin\logic\OrderLogic;
use app\modules\admin\logic\DictionaryLogic;
use app\modules\admin\logic\DeliveredResumeLogic;
use app\modules\admin\models\Job;
use app\components\SMSVerifier;

/**
 * User ActiveRecord model.
 *
 * @property integer $id
 * @property string  $username
 * @property string  $email
 * @property string  $password_hash
 * @property string  $auth_key
 * @property integer $registered_from
 * @property integer $logged_in_from
 * @property integer $logged_in_at
 * @property string  $confirmation_token
 * @property integer $confirmation_sent_at
 * @property integer $confirmed_at
 * @property string  $unconfirmed_email
 * @property string  $recovery_token
 * @property integer $recovery_sent_at
 * @property integer $blocked_at
 * @property string  $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string  $confirmationUrl
 * @property boolean $isConfirmed
 * @property boolean $isConfirmationPeriodExpired
 * @property string  $recoveryUrl
 * @property boolean $isRecoveryPeriodExpired
 * @property boolean $isBlocked
 * // customized
 * @property boolean $phoneno
 * @property string  $company
 * @property integer $type
 *
 * @property \hipstercreative\user\Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class User extends ActiveRecord implements IdentityInterface
{
    use ModuleTrait;

    const EVENT_BEFORE_REGISTER = 'before_register';
    const EVENT_AFTER_REGISTER = 'after_register';

    /**
     * @var string Plain password. Used for model validation.
     */
    public $password;

    /**
     * @var string Current user's password.
     */
    public $current_password;

    /**
     * @var string Verify code
     */
    public $verifyCode;

    public $captcha;

    public $newResumeCount = 0;

    /**
     * @inheritdoc
     */
    public static function collectionName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function attributes() {
        return [
            '_id',
            'id',
            'username',
            'email',
            'password_hash',
            'auth_key',
            'registered_from',
            'logged_in_from',
            'logged_in_at',
            'confirmation_token',
            'confirmation_sent_at',
            'confirmed_at',
            'unconfirmed_email',
            'recovery_token',
            'recovery_sent_at',
            'blocked_at',
            'role',
            'created_at',
            'updated_at',
            'confirmationUrl',
            'isConfirmed',
            'isConfirmationPeriodExpired',
            'recoveryUrl',
            'isRecoveryPeriodExpired',
            'isBlocked',
            'phoneno',
            'company',
            'position',
            'type',
            'nickname',
            'compDesc',
            'authentication', // 是否认证
            'authStatus', // 认证状态 NoAuth - 未认证  Pending - 认证中  Pass - 认证通过  Reject - 认证失败
            'authFailMsg', // 认证失败原因
            'avatar', // 头像
            'jobCount', // 发布的工作个数，取出后统计
            'points', // 个人积分
            'realName', // 真实姓名
            'sex', // 性别
            'card', // 名片url
            'notifyOnNewResume', // 收到新简历时是否通知
            'hasNewResume', // 是否有新简历
        ];
    }

    /**
     * @return \yii\mongodb\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne($this->module->manager->profileClass, ['user_id' => '_id']);
    }

    /**
     * @return \yii\mongodb\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany($this->module->manager->accountClass, ['user_id' => '_id']);
    }

    /**
     * @return array Connected accounts ($provider => $account)
     */
    public function getConnectedAccounts()
    {
        $connected = [];
        $accounts  = $this->accounts;
        foreach ($accounts as $account) {
            $connected[$account->provider] = $account;
        }

        return $connected;
    }

    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => \Yii::t('user', 'Username'),
            'phoneno' => \Yii::t('user', 'Phone Number'),
            'email' => \Yii::t('user', 'Email'),
            'password' => \Yii::t('user', 'Password'),
            'created_at' => \Yii::t('user', 'Registration time'),
            'registered_from' => \Yii::t('user', 'Registered from'),
            'role' => \Yii::t('user', 'Role'),
            'unconfirmed_email' => \Yii::t('user', 'Unconfirmed email'),
            'current_password' => \Yii::t('user', 'Current password'),
            'verifyCode' => \Yii::t('user', 'Verify Code'),
            'company' => \Yii::t('user', 'Company'),
            'position' => \Yii::t('user', 'Position'),
            'type' => \Yii::t('user', 'Type'),
            'authStatus' => \Yii::t('user', 'Auth Status'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return [
            'register'        => ['username', 'password','phoneno', 'type', 'verifyCode', /*'captcha'*/],
            'register_comp'   => ['username', 'password','phoneno', 'type', 'company', 'position', 'verifyCode', /*'captcha'*/],
            'connect'         => ['username', 'email'],
            'create'          => ['username', 'password', 'phoneno', 'type', 'company', 'position'],
            'update'          => ['username', 'email', 'password','phoneno'],
            'update_phone'    => ['phoneno'],
            'update_password' => ['password', 'current_password'],
            'update_email'    => ['unconfirmed_email'],
            'update_info'     => ['nickname', 'realName', 'sex', 'compDesc', 'notifyOnNewResume'],
            'auth'            => ['authStatus', 'authFailMsg'],
            'weixinconnect'   => [],
            'default'         => []
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username rules
            ['username', 'required', 'on' => ['connect', 'create', 'update', 'register', 'register_comp']],
            //['username', 'match', 'pattern' => '/^[a-zA-Z0-9+]\w+$/'],
            ['username', 'string', 'min' => 3, 'max' => 25],
            ['username', 'unique'],
            ['username', 'trim'],

            // nickname rules
            ['nickname', 'string', 'min' => 1, 'max' => 25],

            // realName rules
            ['realName', 'string', 'min' => 0, 'max' => 25],

            //
            ['sex', 'match', 'pattern' => '/^[01]$/'],

            // phoneno rules
            ['phoneno', 'required', 'on' => ['register', 'register_comp', 'connect', 'create', 'update']],
            ['phoneno', 'match', 'pattern' => '/^\d+$/'],
            ['phoneno', 'string', 'min' => 11, 'max' => 11],
            ['phoneno', 'unique'],
            ['phoneno', 'trim'],

            ['company', 'required', 'on' => ['register_comp', 'update']],
            ['company', 'string', 'min' => 4, 'max' => 16],

            ['position', 'required', 'on' => ['register_comp', 'update']],
            ['position', 'string', 'min' => 2, 'max' => 14],

            // definedVipLevel
            //['definedVipLevel', 'required', 'on' => ['create','update']],

            // email rules
            // not used
            /*['email', 'required', 'on' => ['register', 'connect', 'create', 'update', 'update_email']],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique'],
            ['email', 'trim'],*/

            // unconfirmed email rules
            ['unconfirmed_email', 'required', 'on' => 'update_email'],
            ['unconfirmed_email', 'unique', 'targetAttribute' => 'email', 'on' => 'update_email'],
            ['unconfirmed_email', 'email', 'on' => 'update_email'],

            // password rules
            ['password', 'required', 'on' => ['register', 'register_comp', 'update_password']],
            ['password', 'string', 'min' => 6, 'on' => ['register', 'update_password', 'create']],

            // current password rules
            ['current_password', 'required', 'on' => ['update_email', 'update_password']],
            ['current_password', function ($attr) {
                if (!empty($this->$attr) && !Password::validate($this->$attr, $this->password_hash)) {
                    $this->addError($attr, \Yii::t('user', 'Current password is not valid'));
                }
            }, 'on' => ['update_email', 'update_password']],

            ['verifyCode', 'required', 'on' => ['register', 'register_comp']],
            ['verifyCode', function ($attr) {
                if (SMSVerifier::verifyCode($this->phoneno, $this->$attr, false) == false) {
                    $this->addError($attr, \Yii::t('user', 'Verify code is not valid'));
                }
            }, 'on' => ['register', 'register_comp']],

            /*['captcha', 'required', 'on' => ['register', 'register_comp']],
            ['captcha', 'captcha'],*/

            ['authStatus', 'required', 'on' => ['auth']],
            ['authStatus', 'match', 'pattern' => '/^[23]$/', 'on' => ['auth']],

            ['authFailMsg', 'trim'],
            ['authFailMsg', function($attr) {
                if ($this->authStatus == DictionaryLogic::indexKeyValue('AuthStatus', 'Reject') && strlen($this->$attr) <= 0)  {
                    $this->addError($attr, '必须有拒绝原因');
                }
            }, 'on' => ['auth']],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        if (is_array($id) && isset($id['$id'])) {
            $id = new \MongoId($id['$id']);
        }
        return static::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = NULL)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getAttribute('_id');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') == $authKey;
    }

    public function validateRegister() {
        $valid = true;
        if ($this->type == DictionaryLogic::indexKeyValue('UserType', 'CompanyUser')) {
            if (strlen(trim($this->position)) < 2 || strlen(trim($this->position)) > 14) {
                $this->addError('position', \Yii::t('user', 'Position is not valid'));
                $valid = false;
            }
            if (strlen(trim($this->company)) < 4 || strlen(trim($this->company)) > 14) {
                $this->addError('company', \Yii::t('user', 'Company is not valid'));
                $valid = false;
            }
        }
        return $valid;
    }

    /**
     * Creates a user.
     *
     * @return bool
     */
    public function create()
    {
        if ($this->password == null) {
            $this->password = Password::generate(8);
        }

        if ($this->module->confirmable) {
            $this->generateConfirmationData();
        } else {
            $this->confirmed_at = time();
        }

        if ($this->save()) {
            $this->module->mailer->sendWelcomeMessage($this);
            return true;
        }

        return false;
    }

    /**
     * This method is called at the beginning of user registration process.
     */
    protected function beforeRegister()
    {
        $this->trigger(self::EVENT_BEFORE_REGISTER);

        $this->setAttribute('registered_from', ip2long(\Yii::$app->request->userIP));
        $this->nickname = $this->username;
        $this->compDesc = '暂无简介';
        $this->authentication = false;
        $this->notifyOnNewResume = true;
        $this->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'NoAuth');
        $this->points = 0;
        $this->avatar = DictionaryLogic::indexKeyValue('Default', 'Avatar', false);

        if ($this->module->confirmable) {
            $this->generateConfirmationData();
        }
    }

    /**
     * This method is called at the end of user registration process.
     */
    protected function afterRegister()
    {
        /*if ($this->module->confirmable) {
            $this->module->mailer->sendConfirmationMessage($this);
        }*/

        SMSVerifier::deleteCodeCache($this->phoneno);
        $this->confirm(false);

         \Yii::$app->user->login($this);

        $this->trigger(self::EVENT_AFTER_REGISTER);
    }

    /**
     * Registers a user.
     *
     * @return bool
     * @throws \RuntimeException
     */
    public function register()
    {
        if (!$this->getIsNewRecord()) {
            throw new \RuntimeException('Calling "'.__CLASS__.'::register()" on existing user');
        }

        if ($this->validate()/* && $this->validateRegister()*/) {
            $this->beforeRegister();
            if ($this->save(false)) {
                $this->afterRegister();
                return true;
            }
        }

        return false;
    }

    /**
     * Updates email with new one. If confirmable option is enabled, it will send confirmation message to new email.
     *
     * @return bool
     */
    public function updateEmail()
    {
        if ($this->validate()) {
            if ($this->module->confirmable) {
                $this->confirmation_token = \Yii::$app->getSecurity()->generateRandomString();
                $this->confirmation_sent_at = time();
                $this->confirmed_at =null;
                $this->authentication = false;
                $this->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'Pending');
                $this->email = '';
                $this->save(false);
                $this->module->mailer->sendReconfirmationMessage($this);
            } else {
                $this->email = $this->unconfirmed_email;
                $this->unconfirmed_email = null;
            }

            return true;
        }

        return false;
    }

    /**
     * Resets unconfirmed email and confirmation data.
     */
    public function resetEmailUpdate()
    {
        if ($this->module->confirmable && !empty($this->unconfirmed_email)) {
            $this->unconfirmed_email = null;
            $this->confirmation_token = null;
            $this->confirmation_sent_at = null;
            $this->save(false);
        }
    }

    /**
     * Confirms a user by setting it's "confirmation_time" to actual time
     *
     * @param  bool $runValidation Whether to check if user has already been confirmed or confirmation token expired.
     * @return bool
     */
    public function confirm($runValidation = true)
    {
        if ($runValidation) {
            if ($this->getIsConfirmed()) {
                return true;
            } elseif ($this->getIsConfirmationPeriodExpired()) {
                return false;
            }
        }

        if (empty($this->unconfirmed_email)) {
            $this->confirmed_at = time();
        } else {
            $this->email = $this->unconfirmed_email;
            $this->unconfirmed_email = null;
            $this->confirmed_at = time();
        }

        $this->confirmation_token = null;
        $this->confirmation_sent_at = null;
        $saveResult = $this->save(false);
        return $saveResult;
    }

    /**
     * Generates confirmation data and re-sends confirmation instructions by email.
     *
     * @return bool
     */
    public function resend()
    {
        $this->generateConfirmationData();
        $this->save(false);

        return $this->module->mailer->sendConfirmationMessage($this);
    }

    /**
     * Generates confirmation data.
     */
    protected function generateConfirmationData()
    {
        $this->confirmation_token = \Yii::$app->getSecurity()->generateRandomString();
        $this->confirmation_sent_at = time();
        $this->confirmed_at = null;
    }

    /**
     * @return string Confirmation url.
     */
    public function getConfirmationUrl()
    {
        if (is_null($this->confirmation_token)) {
            return null;
        }

        return Url::toRoute(['/user/registration/confirm', 'id' => (string)$this->_id, 'token' => $this->confirmation_token], true);
    }

    /**
     * Verifies whether a user is confirmed or not.
     *
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->confirmed_at !== null;
    }

    /**
     * Checks if the user confirmation happens before the token becomes invalid.
     *
     * @return bool
     */
    public function getIsConfirmationPeriodExpired()
    {
        return $this->confirmation_sent_at != null && ($this->confirmation_sent_at + $this->module->confirmWithin) < time();
    }

    /**
     * Resets password and sets recovery token to null
     *
     * @param  string $password
     * @return bool
     */
    public function resetPassword($password)
    {
        $this->password = $password;
        $this->setAttributes([
            'recovery_token'   => null,
            'recovery_sent_at' => null
        ], false);

        return $this->save(false);
    }

    /**
     * Checks if the password recovery happens before the token becomes invalid.
     *
     * @return bool
     */
    public function getIsRecoveryPeriodExpired()
    {
        return ($this->recovery_sent_at + $this->module->recoverWithin) < time();
    }

    /**
     * @return string Recovery url
     */
    public function getRecoveryUrl()
    {
        return Url::toRoute(['/user/recovery/reset',
            'id' => (string)$this->_id,
            'token' => $this->recovery_token
        ], true);
    }

    /**
     * Generates recovery data and sends recovery message to user.
     */
    public function sendRecoveryMessage()
    {
        $this->recovery_token = \Yii::$app->getSecurity()->generateRandomString();
        $this->recovery_sent_at = time();
        $this->save(false);

        return $this->module->mailer->sendRecoveryMessage($this);
    }

    /**
     * Blocks the user by setting 'blocked_at' field to current time.
     */
    public function block()
    {
        $this->blocked_at = time();

        return $this->save(false);
    }

    /**
     * Blocks the user by setting 'blocked_at' field to null.
     */
    public function unblock()
    {
        $this->blocked_at = null;

        return $this->save(false);
    }

    /**
     * @return bool Whether user is blocked.
     */
    public function getIsBlocked()
    {
        return !is_null($this->blocked_at);
    }

    public function beforeValidate() {
        if (!parent::beforeValidate()) {
            return false;
        }
        if ($this->scenario != 'auth') {
            return true;
        }
        if ($this->authStatus == DictionaryLogic::indexKeyValue('AuthStatus', 'Reject') && strlen(trim($this->authFailMsg)) <= 0)  {
            $this->addError('authFailMsg', '必须有拒绝原因');
            return false;
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->setAttribute('auth_key', \Yii::$app->getSecurity()->generateRandomString());
            $this->setAttribute('role', $this->module->defaultRole);
        }

        if (!empty($this->password)) {
            $this->setAttribute('password_hash', Password::hash($this->password));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert,$attr)
    {
        if ($insert) {
            $profile = $this->module->manager->createProfile([
                'user_id'        => $this->_id,
                'gravatar_email' => $this->email
            ]);
            $profile->save(false);
        }
        parent::afterSave($insert,$attr);
    }

    public function afterFind() {
        if ($this->nickname == null) {
            $this->nickname = $this->username;
        }
        if ($this->avatar == null || $this->avatar == '') {
            $this->avatar = DictionaryLogic::indexKeyValue('Default', 'Avatar', false);
        }
        if ($this->compDesc == null) {
            $this->compDesc = '暂无简介';
        }
        if ($this->notifyOnNewResume == null) {
            $this->notifyOnNewResume = true;
        }
        if ($this->hasNewResume == null) {
            $this->hasNewResume = false;
        }
        /*if ($this->authentication == null) {
            $this->authentication = false;
            $this->authStatus = DictionaryLogic::indexKeyValue('AuthStatus', 'NoAuth');
        }*/
        if ($this->points == null) {
            $this->points = 0;
        }
        $this->jobCount = Job::find()->where(['publisher' => $this->_id, 'status' => DictionaryLogic::indexKeyValue('JobStatus', 'Pass')])->count();
        $this->newResumeCount = DeliveredResumeLogic::getUnopenedCount($this->_id);
    }
}
