<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_publicremark".
 *
 * @property string $id
 * @property string $user_id
 * @property integer $cde_id
 * @property string $remark
 * @property string $create_date
 * @property string $public_remark
 */
class CdePublicremark extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cde_publicremark';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'cde_id'], 'integer'],
            [['create_date'], 'safe'],
            [['remark', 'public_remark'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User_id',
            'cde_id' => 'Cde ID',
            'remark' => 'Remark',
            'create_date' => 'Create Date',
            'public_remark' => 'Public Remark',
        ];
    }
}
