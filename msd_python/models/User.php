<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $name
 */
class User extends \yii\db\ActiveRecord {

    public static $currUser;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['email', 'created_at', 'updated_at'], 'required'],
                [['role', 'status', 'created_at', 'updated_at'], 'integer'],
                [['email', 'name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'name' => 'Name',
        ];
    }

    public static function getUserfavorite($mail) {
        $user_favorite = User::find()->select('count(cde_favorite.id) as user_favorite')->innerJoin('cde_favorite', 'user.id = cde_favorite.user_id')->andWhere('user.email = :email', [':email' => $mail])->asArray()->one();

        return (int) $user_favorite['user_favorite'];
    }

}
