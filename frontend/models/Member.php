<?php

namespace frontend\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "member".
 *
 * @property int $id
 * @property string $name 用户名
 * @property string $auth_key
 * @property string $password_hash 密码
 * @property string $email 邮箱
 * @property int $tel 电话
 * @property int $last_login_time 最后登录时间
 * @property int $last_login_ip 最后登录ip
 * @property int $status 状态
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Member extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $captcha;
    public $repassroed;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'member';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'password_hash', 'email', 'tel'], 'required'],
            [['tel', 'last_login_time', 'last_login_ip', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'email'], 'string', 'max' => 100],
            [['name'], 'unique'],
            [['email'], 'unique'],
            [['tel'], 'unique'],
            ['captcha','captcha','captchaAction'=>'site/captcha'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => '密码',
            'email' => '邮箱',
            'tel' => '电话',
            'last_login_time' => '最后登录时间',
            'last_login_ip' => '最后登录ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }

    // 保存之前要做的事
    public function beforeSave($insert)
    {
        if($insert){
            // 密码加密
            $this->password_hash = \Yii::$app->security->generatePasswordHash($this->password_hash);
            // auth_key  生成随机字符串
            $this->status = 0;
            $this->auth_key = \Yii::$app->security->generateRandomString();
            // 添加时间
            $this->created_at = time();
        }else{
            $this->updated_at = time();
        }
        return parent::beforeSave($insert); // 必须返回true  save才执行


    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key == $authKey;
    }
}
