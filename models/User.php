<?php

namespace app\models;

use app\modules\master\models\Userinstr;
use Yii;
use yii\web\IdentityInterface;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $auth_key
 * @property string $created_at
 * @property string $updated_at
 * @property string $date_secret_key
 * @property string $secret_key
 * @property integer $status
 * @property integer $letter_status
 */
class User extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;
    //letter status const
    const STATUS_LETTER_SENT = 1;
    const STATUS_LETTER_NOT_SENT = 0;

    public $username;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'status', 'letter_status'], 'required'],
            [['first_name', 'last_name', 'email', 'password'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['email', 'unique', 'message' => 'User with this email has been registered already.'],
            [['created_at', 'updated_at', 'date_secret_key'], 'safe'],
            [['first_name', 'last_name', 'email', 'password', 'auth_key'], 'string', 'max' => 255],
            ['secret_key', 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id User',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'password' => 'Password',
            'auth_key' => 'Auth Key',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'status' => 'Status',
            'letter_status' => 'Letter Status',
        ];
    }


    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                // если вместо метки времени UNIX используется datetime:
                'value' => new Expression('NOW()'),
            ]
        ];
    }

//    helpers

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function setPassword($password)
    {
        $this->password = Yii::$app->security->generatePasswordHash($password);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password);
    }

    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email,
        ]);
    }

    public static function isSecretKeyExpire($key)
    {
        if (empty($key)){
            return false;
        }

        $expire = Yii::$app->params['secretKeyExpire'];
        $user = self::findBySecretKey($key);

        $timestamp = $user->date_secret_key;

        return $timestamp+$expire >= time();
    }

    public static function findBySecretKey($key)
    {
        return self::findOne([
            'secret_key' => $key,
        ]);
    }

    public static function deleteUserById($id){
        Yii::$app->db->createCommand()->delete('auth_assignment', 'user_id = '.$id)->execute();
        Yii::$app->db->createCommand()->delete('userinstr', 'user_id = '.$id.'')->execute();
        Yii::$app->db->createCommand()->delete('user', 'id = '.$id.'')->execute();
    }

    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString();
        $this->date_secret_key = time();
    }

    public function removeSecretKey()
    {
        $this->secret_key = null;
    }

//    User Authentication

    public static function findIdentity($id)
    {
        return static::findOne([
            'id' => $id,
        ]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFirstName(){

        return $this->first_name;
    }

    public function getLastName(){

        return $this->last_name;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function userActivationList(){
        $query = Yii::$app->db->createCommand(
            'SELECT u.id, r.item_name, u.first_name, u.last_name, u.email, u.`status`, u.letter_status, u.secret_key, u.date_secret_key 
                FROM `user` u, auth_assignment r
                WHERE u.id = r.user_id'
        )->queryAll();

        foreach ($query as $value){
            $list[$value['item_name']][] = $value;
        }

        return $list;
    }

    public function getUsername(){
        return $this->username = "$this->first_name $this->last_name";
    }

    public function getUserLessons(){

        return Userinstr::find()
            ->joinWith(['instricon instr'])
            ->where(['user_id' => $this->id])
            ->asArray()
            ->all();
    }

    public static function userListDropBox(){
        $rows = static::find()
            ->select(['id', 'first_name', 'last_name'])
            ->from('user');
        if(!self::isMaster()){
            $rows->andWhere(['id' => Yii::$app->user->id]);
        }
        $rows->andWhere(['status' => 10]);
        $rows = $rows->all();

        foreach ($rows as $value){
            $user_list[$value['id']] = $value['first_name'].' '.$value['last_name'];
        }

        return $user_list;
    }

    public static function isMaster(){
        if (key(Yii::$app->authManager->getRolesByUser(Yii::$app->user->id)) == 'master'){
            return true;
        }else{
            return false;
        }
    }

    public function userRole(){
        return key(Yii::$app->authManager->getRolesByUser($this->id));
    }
}
