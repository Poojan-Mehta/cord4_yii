<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function rules()
    {
        return [
            [['user_name', 'user_email', 'user_mobile_no', 'user_type', 'user_password'], 'required'],
            [['user_email'], 'email'],
            [['user_email'], 'unique'],
            [['user_status'], 'default', 'value' => 0],
            //[['otp'], 'integer'], // OTP should be an integer
            [['id', 'created_at', 'updated_at', 'otp'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Only hash the password if it’s new or changed
            if ($this->isAttributeChanged('user_password')) {
                $this->user_password = password_hash($this->user_password, PASSWORD_DEFAULT);
            }
            return true;
        }
        return false;
    }

    public function validatePassword($user_password)
    {
        return password_verify($user_password, $this->user_password);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * @return IdentityInterface|null the identity object that matches the given token
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // If you’re not using access tokens, you can return null
        return null;
    }

    /**
     * Returns the ID of the user.
     *
     * @return int|string the ID of the user
     */
    public function getId()
    {
        return $this->id; // Adjust this if your primary key column is different
    }

    /**
     * Returns an auth key for the user, used for "remember me" functionality.
     *
     * @return string|null the auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key; // Make sure this field exists in your database, or return null if unused
    }

    /**
     * Validates the given auth key.
     *
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

}
