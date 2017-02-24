<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cde_light".
 *
 * @property string $id
 * @property string $cde_id
 * @property integer $type
 * @property integer $sub_type
 * @property string $change_date
 */
class CdeLight extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cde_light';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cde_id', 'type', 'sub_type'], 'integer'],
            [['change_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cde_id' => 'Cde ID',
            'type' => 'Type',
            'sub_type' => 'Sub Type',
            'change_date' => 'Change Date',
        ];
    }
}
