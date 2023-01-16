<?php

namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use \yii\helpers\ArrayHelper;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends BaseActiveRecord implements IdentityInterface
{
	const STATUS_INACTIVE = 0;
	const STATUS_ACTIVE = 1;

	const FULL_NAME_TEMPLATE = '{last_name} {first_name}';

	/**
	 * @var array|string
	 */
	public $auth_item_names;

	/**
	 * @var string
	 */
	public $password;

	/**
	 * @var string
	 */
	public $password_repeat;

	/**
	 * {@inheritdoc}
	 */
	public static function tableName()
	{
		return 'user';
	}

	/**
	 * {@inheritdoc}
	 */
	public function behaviors()
	{
		return [
			TimestampBehavior::class,
		];
	}

	/**
	 * {@inheritdoc}
	 */
	public function rules()
	{
		return [
			[['username', 'first_name', 'last_name', 'email'], 'string', 'max' => 64],
			['email', 'email'],
			['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],

			// password rules
			[['password', 'password_repeat'], 'filter', 'filter' => 'trim', 'on'=> ['create', 'change-password']],
			[['password', 'password_repeat'], 'required','on'=>['create', 'change-password']],
			[['password'], 'string', 'min' => 6, 'max' => 32, 'on'=>['create', 'change-password']],
			['password_repeat', 'compare', 'compareAttribute' => 'password', 'on'=> ['create', 'change-password']],
			// first_name, last_name, email
			[['first_name', 'last_name', 'email'], 'filter', 'filter' => 'trim', 'on'=> ['create', 'update', 'profile']],
			[['first_name', 'last_name', 'email'], 'required','on'=>['create', 'update', 'profile']],
			['email', 'unique', 'on' => ['create', 'update', 'profile']],

			[['auth_item_names'], 'safe'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'username' => Yii::t('app','Логин'),
			'email' => Yii::t('app','Email'),
			'first_name' => Yii::t('app','Имя'),
			'last_name' => Yii::t('app','Фамилия'),
			'fullName' => Yii::t('app','Имя'),
			'full_name' => Yii::t('app','Имя'),
			'status' => Yii::t('app','Активен'),
			'password' => Yii::t('app','Пароль'),
			'password_repeat' => Yii::t('app','Повторить пароль'),
			'created_at' => Yii::t('app','Добавлен'),
			'updated_at' => Yii::t('app','Отредактирован'),
			'auth_item_names' => Yii::t('app','Роли'),
		];
	}

	/**
	 * @inheritdoc
	 */
	public function afterSave($insert, $changedAttributes)
	{
		$this->logSave($this->username, $insert, $changedAttributes);
		parent::afterSave($insert, $changedAttributes);
	}

	/**
	 * @inheritdoc
	 */
	public function beforeDelete()
	{
		Yii::$app->authManager->revokeAll($this->id);
		$this->logDelete($this->username);
		return parent::beforeDelete();
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentity($id)
	{
		return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * {@inheritdoc}
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
	}

	/**
	 * Finds user by username
	 *
	 * @param string $username
	 * @return static|null
	 */
	public static function findByUsername($username)
	{
		return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
	}

	/**
	 * Finds user by password reset token
	 *
	 * @param string $token password reset token
	 * @return static|null
	 */
	public static function findByPasswordResetToken($token)
	{
		if (!static::isPasswordResetTokenValid($token)) {
			return null;
		}

		return static::findOne([
			'password_reset_token' => $token,
			'status' => self::STATUS_ACTIVE,
		]);
	}

	/**
	 * Finds user by verification email token
	 *
	 * @param string $token verify email token
	 * @return static|null
	 */
	public static function findByVerificationToken($token) {
		return static::findOne([
			'verification_token' => $token,
			'status' => self::STATUS_INACTIVE
		]);
	}

	/**
	 * Finds out if password reset token is valid
	 *
	 * @param string $token password reset token
	 * @return bool
	 */
	public static function isPasswordResetTokenValid($token)
	{
		if (empty($token)) {
			return false;
		}

		$timestamp = (int) substr($token, strrpos($token, '_') + 1);
		$expire = Yii::$app->params['user.passwordResetTokenExpire'];
		return $timestamp + $expire >= time();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->getPrimaryKey();
	}

	/**
	 * {@inheritdoc}
	 */
	public function getAuthKey()
	{
		return $this->auth_key;
	}

	/**
	 * {@inheritdoc}
	 */
	public function validateAuthKey($authKey)
	{
		return $this->getAuthKey() === $authKey;
	}

	/**
	 * Validates password
	 *
	 * @param string $password password to validate
	 * @return bool if password provided is valid for current user
	 */
	public function validatePassword($password)
	{
		return Yii::$app->security->validatePassword($password, $this->password_hash);
	}

	/**
	 * Generates password hash from password and sets it to the model
	 *
	 * @param string $password
	 */
	public function setPasswordHash($password)
	{
		$this->password_hash = Yii::$app->security->generatePasswordHash($password);
	}

	/**
	 * Generates "remember me" authentication key
	 */
	public function generateAuthKey()
	{
		$this->auth_key = Yii::$app->security->generateRandomString();
	}

	/**
	 * Generates new password reset token
	 */
	public function generatePasswordResetToken()
	{
		$this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Generates new token for email verification
	 */
	public function generateEmailVerificationToken()
	{
		$this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
	}

	/**
	 * Removes password reset token
	 */
	public function removePasswordResetToken()
	{
		$this->password_reset_token = null;
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthItems()
	{
		return $this->hasMany(AuthItem::class, ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAuthAssignments()
	{
		return $this->hasMany(AuthAssignment::class, ['user_id' => 'id']);
	}

	/**
	 *
	 * @param string $delimiter the delimiter - defaults to ', '
	 * @return string
	 */
	public function getAuthItemNames($delimiter = ', ')
	{
		$items = ArrayHelper::map($this->authItems, 'name', 'description');
		return implode($delimiter, $items);
	}

	/**
	 *
	 * @return array
	 */
	public function getRoles()
	{
		Yii::$app->authManager->getRolesByUser($this->id);
	}

	/**
	 *
	 * @param string $delimiter the delimiter - defaults to ', '
	 * @return string
	 */
	public function getRoleNames($delimiter = ', ')
	{
		return implode($delimiter, ArrayHelper::column($this->getRoles(), 'description'));
	}

	/**
	 * Обновление ролей пользователя
	 */
	public function updateRoles()
	{
		$auth = Yii::$app->authManager;

		if ($ids = $this->auth_item_names) {
			$old_ids = [];
			foreach ($this->authAssignments as $model) {
				$old_ids[] = $model->item_name;
				if (!in_array($model->item_name, $ids) && ($role = $auth->getRole($model->item_name))) {
					$auth->revoke($role, $this->id);
				}
			}
			foreach ($ids as $id) {
				if (!in_array($id, $old_ids) && ($role = $auth->getRole($id))) {
					$auth->assign($role, $this->id);
				}
			}
		} else {
			$auth->revokeAll($this->id);
		}
	}

	/*
	 * Полное имя пользователя
	 *
 	 * @return string
	 */
	public function getFullName()
	{
		return trim(Yii::t('app', self::FULL_NAME_TEMPLATE, ['first_name' => $this->first_name, 'last_name' => $this->last_name]));
	}

	/**
	 * @inheritdoc
	 * @return UserQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new UserQuery(get_called_class());
	}

}
